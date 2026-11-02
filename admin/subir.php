<?php
/** Recibe imágenes que se insertan dentro del artículo (desde el editor). Responde JSON. */
require __DIR__ . '/_auth.php';
require ROOT . '/app/uploads.php';

header('Content-Type: application/json; charset=UTF-8');
header('X-Robots-Tag: noindex');
if (!admin_user()) {
    http_response_code(401);
    echo json_encode(['error' => 'Tu sesión expiró. Vuelve a entrar.']);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}
csrf_check();
try {
    $name = pathinfo((string) ($_FILES['image']['name'] ?? 'imagen'), PATHINFO_FILENAME);
    $url = upload_inline($_FILES['image'] ?? [], $name ?: 'imagen');
    echo json_encode(['url' => $url]);
} catch (RuntimeException $e) {
    http_response_code(422);
    echo json_encode(['error' => $e->getMessage()]);
}
