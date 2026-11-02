<?php
declare(strict_types=1);

/** Clave secreta local (se genera sola la primera vez, fuera del alcance web). */
function app_secret(): string
{
    $file = ROOT . '/storage/secret.key';
    if (!is_file($file)) {
        @mkdir(dirname($file), 0750, true);
        file_put_contents($file, bin2hex(random_bytes(32)));
        @chmod($file, 0640);
    }
    return trim((string) file_get_contents($file));
}

/** Token con la hora en que se mostró el formulario: descarta envíos de robots demasiado rápidos o viejos. */
function contact_token(): string
{
    $t = (string) time();
    return $t . '.' . hash_hmac('sha256', $t, app_secret());
}

function contact_token_valid(string $token): bool
{
    [$t, $sig] = array_pad(explode('.', $token, 2), 2, '');
    if (!ctype_digit($t) || !hash_equals(hash_hmac('sha256', $t, app_secret()), $sig)) {
        return false;
    }
    $age = time() - (int) $t;
    return $age >= 3 && $age <= 60 * 60 * 6;
}

function contact_handle(): array
{
    $state = ['ok' => isset($_GET['enviado']), 'error' => null, 'old' => []];
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
        return $state;
    }
    $in = [
        'nombre' => trim((string) ($_POST['nombre'] ?? '')),
        'telefono' => trim((string) ($_POST['telefono'] ?? '')),
        'email' => trim((string) ($_POST['email'] ?? '')),
        'asunto' => trim((string) ($_POST['asunto'] ?? '')),
        'mensaje' => trim((string) ($_POST['mensaje'] ?? '')),
    ];
    $state['old'] = $in;

    // Robots: campo trampa lleno o token inválido → se responde como si todo saliera bien.
    if (($_POST['sitio_web'] ?? '') !== '' || !contact_token_valid((string) ($_POST['t'] ?? ''))) {
        redirect('/contacto/?enviado=1', 303);
    }
    if ($in['nombre'] === '' || $in['telefono'] === '' || $in['mensaje'] === '' || !filter_var($in['email'], FILTER_VALIDATE_EMAIL)) {
        $state['error'] = 'Por favor llena tu nombre, teléfono, un correo válido y tu mensaje.';
        return $state;
    }
    $in['nombre'] = mb_substr($in['nombre'], 0, 120);
    $in['asunto'] = mb_substr($in['asunto'], 0, 120);
    $in['mensaje'] = mb_substr($in['mensaje'], 0, 4000);
    $ip = (string) ($_SERVER['REMOTE_ADDR'] ?? '');

    try {
        $st = db()->prepare("SELECT COUNT(*) FROM messages WHERE ip = ? AND created_at >= ?");
        $st->execute([$ip, date('Y-m-d H:i:s', time() - 3600)]);
        if ((int) $st->fetchColumn() >= 5) {
            $state['error'] = 'Recibimos varios mensajes desde tu conexión. Intenta más tarde o escríbenos por WhatsApp.';
            return $state;
        }
        $ins = db()->prepare('INSERT INTO messages (name, email, phone, subject, body, ip, created_at) VALUES (?,?,?,?,?,?,?)');
        $ins->execute([$in['nombre'], $in['email'], $in['telefono'], $in['asunto'], $in['mensaje'], $ip, now()]);
        $id = (int) db()->lastInsertId();
    } catch (Throwable $e) {
        error_log('[mochcun] contacto BD: ' . $e->getMessage());
        $id = 0;
    }

    $sent = contact_mail($in);
    if ($id && $sent) {
        db()->prepare('UPDATE messages SET mailed = 1 WHERE id = ?')->execute([$id]);
    }
    if (!$id && !$sent) {
        $state['error'] = 'No pudimos enviar tu mensaje. Escríbenos por WhatsApp o al correo ' . config('email') . '.';
        return $state;
    }
    redirect('/contacto/?enviado=1', 303);
}

function contact_mail(array $in): bool
{
    $clean = fn (string $s) => trim(str_replace(["\r", "\n"], ' ', $s));
    $subject = 'Contacto web: ' . $clean($in['asunto'] ?: 'Mensaje');
    $body = "Nuevo mensaje desde mochcun.com\n\n"
        . 'Nombre: ' . $clean($in['nombre']) . "\n"
        . 'Teléfono: ' . $clean($in['telefono']) . "\n"
        . 'Correo: ' . $clean($in['email']) . "\n"
        . 'Asunto: ' . $clean($in['asunto']) . "\n\n"
        . $in['mensaje'] . "\n\n"
        . "También puedes verlo en el panel: " . url('/admin/mensajes.php') . "\n";
    $headers = [
        'From' => 'MOCHCUN Web <' . config('mail_from') . '>',
        'Reply-To' => $clean($in['nombre']) . ' <' . $clean($in['email']) . '>',
        'Content-Type' => 'text/plain; charset=UTF-8',
        'X-Mailer' => 'mochcun.com',
    ];
    return @mail((string) config('mail_to'), '=?UTF-8?B?' . base64_encode($subject) . '?=', $body, $headers, '-f' . config('mail_from'));
}
