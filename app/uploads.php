<?php
declare(strict_types=1);

/**
 * Procesa imágenes subidas desde el panel: valida, corrige orientación,
 * redimensiona y guarda en WebP (o JPG si el servidor no soporta WebP).
 */
const UPLOAD_MAX_BYTES = 15 * 1024 * 1024;
const UPLOAD_MAX_PIXELS = 40_000_000;

function upload_error_message(int $code): string
{
    return match ($code) {
        UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'La imagen pesa demasiado para el servidor.',
        UPLOAD_ERR_PARTIAL => 'La imagen se subió incompleta. Intenta de nuevo.',
        UPLOAD_ERR_NO_FILE => 'No se seleccionó ninguna imagen.',
        default => 'No se pudo subir la imagen (código ' . $code . ').',
    };
}

/** Abre la imagen subida como recurso GD ya orientado. Lanza RuntimeException con mensaje para el usuario. */
function upload_open(array $file): GdImage
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        throw new RuntimeException(upload_error_message((int) ($file['error'] ?? UPLOAD_ERR_NO_FILE)));
    }
    if (!is_uploaded_file($file['tmp_name']) && PHP_SAPI !== 'cli') {
        throw new RuntimeException('Archivo no válido.');
    }
    if ($file['size'] > UPLOAD_MAX_BYTES) {
        throw new RuntimeException('La imagen pesa más de 15 MB. Usa una más ligera.');
    }
    $info = @getimagesize($file['tmp_name']);
    if (!$info || !in_array($info[2], [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP, IMAGETYPE_GIF], true)) {
        throw new RuntimeException('Formato no permitido. Usa JPG, PNG o WebP.');
    }
    if ($info[0] * $info[1] > UPLOAD_MAX_PIXELS) {
        throw new RuntimeException('La imagen es demasiado grande en píxeles. Redúcela a menos de 6000 px por lado.');
    }
    @ini_set('memory_limit', '384M');
    $img = @imagecreatefromstring((string) file_get_contents($file['tmp_name']));
    if (!$img) {
        throw new RuntimeException('No se pudo leer la imagen.');
    }
    if ($info[2] === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
        $exif = @exif_read_data($file['tmp_name']);
        $o = (int) ($exif['Orientation'] ?? 1);
        $rot = [3 => 180, 6 => -90, 8 => 90][$o] ?? 0;
        if ($rot) {
            $r = imagerotate($img, $rot, 0);
            if ($r) {
                $img = $r;
            }
        }
    }
    if (!imageistruecolor($img)) {
        $tc = imagecreatetruecolor(imagesx($img), imagesy($img));
        imagealphablending($tc, false);
        imagesavealpha($tc, true);
        imagecopy($tc, $img, 0, 0, 0, 0, imagesx($img), imagesy($img));
        $img = $tc;
    }
    return $img;
}

function upload_resize(GdImage $src, int $maxW): GdImage
{
    $w = imagesx($src);
    $h = imagesy($src);
    if ($w <= $maxW) {
        return $src;
    }
    $nh = (int) round($h * $maxW / $w);
    $dst = imagecreatetruecolor($maxW, $nh);
    imagealphablending($dst, false);
    imagesavealpha($dst, true);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $maxW, $nh, $w, $h);
    return $dst;
}

/** Recorta al centro con la proporción dada y escala a w×h. */
function upload_cover_crop(GdImage $src, int $w, int $h): GdImage
{
    $sw = imagesx($src);
    $sh = imagesy($src);
    $target = $w / $h;
    if ($sw / $sh > $target) {
        $cw = (int) round($sh * $target);
        $ch = $sh;
        $x = (int) (($sw - $cw) / 2);
        $y = 0;
    } else {
        $cw = $sw;
        $ch = (int) round($sw / $target);
        $x = 0;
        $y = (int) (($sh - $ch) / 2);
    }
    $dst = imagecreatetruecolor($w, $h);
    $white = imagecolorallocate($dst, 255, 255, 255);
    imagefill($dst, 0, 0, $white);
    imagecopyresampled($dst, $src, 0, 0, $x, $y, $w, $h, $cw, $ch);
    return $dst;
}

function upload_webp_supported(): bool
{
    return function_exists('imagewebp') && (gd_info()['WebP Support'] ?? false);
}

function upload_save(GdImage $img, string $absNoExt, ?string $force = null): string
{
    $ext = $force ?? (upload_webp_supported() ? 'webp' : 'jpg');
    $file = $absNoExt . '.' . $ext;
    if ($ext === 'webp') {
        imagewebp($img, $file, 78);
    } else {
        // JPG no tiene transparencia: fondo blanco
        $bg = imagecreatetruecolor(imagesx($img), imagesy($img));
        imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
        imagecopy($bg, $img, 0, 0, 0, 0, imagesx($img), imagesy($img));
        imageinterlace($bg, true);
        imagejpeg($bg, $file, 80);
    }
    @chmod($file, 0644);
    return $file;
}

/** Carpeta /uploads/blog/AAAA/MM y nombre base único. Regresa [rutaAbsolutaSinExt, rutaPublicaSinExt]. */
function upload_target(string $name): array
{
    $sub = date('Y/m');
    $dir = ROOT . '/uploads/blog/' . $sub;
    if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
        throw new RuntimeException('No se pudo crear la carpeta de imágenes.');
    }
    $base = slugify($name) . '-' . bin2hex(random_bytes(3));
    return [$dir . '/' . $base, '/uploads/blog/' . $sub . '/' . $base];
}

/** Portada: 1600 px, 800 px y 1200×630 para redes sociales. Regresa la URL pública de la versión 1600. */
function upload_cover(array $file, string $name): string
{
    $img = upload_open($file);
    [$abs, $pub] = upload_target($name);
    $large = upload_save(upload_resize($img, 1600), $abs . '-1600');
    upload_save(upload_resize($img, 800), $abs . '-800');
    upload_save(upload_cover_crop($img, 1200, 630), $abs . '-og', 'jpg');
    return $pub . '-1600.' . pathinfo($large, PATHINFO_EXTENSION);
}

/** Imagen dentro del artículo: máximo 1200 px. Regresa URL pública. */
function upload_inline(array $file, string $name): string
{
    $img = upload_open($file);
    [$abs, $pub] = upload_target($name);
    $saved = upload_save(upload_resize($img, 1200), $abs . '-1200');
    return $pub . '-1200.' . pathinfo($saved, PATHINFO_EXTENSION);
}

/** Borra los archivos de una portada anterior. */
function upload_delete_cover(string $large): void
{
    if (!preg_match('#^/uploads/blog/[A-Za-z0-9/_.-]+-1600\.(webp|jpg)$#', $large)) {
        return;
    }
    foreach (cover_urls($large) as $u) {
        $f = ROOT . $u;
        if (is_file($f)) {
            @unlink($f);
        }
    }
}
