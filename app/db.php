<?php
declare(strict_types=1);

/** Conexión única a SQLite. Crea las tablas la primera vez. */
function db(): PDO
{
    static $pdo = null;
    if ($pdo) {
        return $pdo;
    }
    $path = (string) config('db_path');
    $dir = dirname($path);
    if (!is_dir($dir)) {
        mkdir($dir, 0750, true);
    }
    $pdo = new PDO('sqlite:' . $path, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $pdo->exec('PRAGMA journal_mode = WAL; PRAGMA foreign_keys = ON; PRAGMA busy_timeout = 4000;');
    migrate($pdo);
    return $pdo;
}

function migrate(PDO $pdo): void
{
    $version = (int) $pdo->query('PRAGMA user_version')->fetchColumn();
    if ($version < 1) {
        $pdo->exec(<<<SQL
            CREATE TABLE IF NOT EXISTS posts (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                slug TEXT NOT NULL UNIQUE,
                title TEXT NOT NULL,
                seo_title TEXT NOT NULL DEFAULT '',
                description TEXT NOT NULL DEFAULT '',
                category TEXT NOT NULL DEFAULT 'noticias',
                cover TEXT NOT NULL DEFAULT '',
                cover_alt TEXT NOT NULL DEFAULT '',
                content TEXT NOT NULL DEFAULT '',
                status TEXT NOT NULL DEFAULT 'borrador',
                published_at TEXT,
                created_at TEXT NOT NULL,
                updated_at TEXT NOT NULL,
                author_id INTEGER
            );
            CREATE INDEX IF NOT EXISTS posts_status_date ON posts(status, published_at);
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                email TEXT NOT NULL UNIQUE,
                password_hash TEXT NOT NULL,
                created_at TEXT NOT NULL,
                last_login_at TEXT
            );
            CREATE TABLE IF NOT EXISTS messages (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                email TEXT NOT NULL,
                phone TEXT NOT NULL DEFAULT '',
                subject TEXT NOT NULL DEFAULT '',
                body TEXT NOT NULL,
                ip TEXT NOT NULL DEFAULT '',
                mailed INTEGER NOT NULL DEFAULT 0,
                read_at TEXT,
                created_at TEXT NOT NULL
            );
            CREATE TABLE IF NOT EXISTS login_attempts (
                ip TEXT NOT NULL,
                attempted_at INTEGER NOT NULL
            );
            PRAGMA user_version = 1;
        SQL);
    }
    if ($version < 2) {
        $pdo->exec(<<<SQL
            CREATE TABLE IF NOT EXISTS slug_redirects (
                old_slug TEXT PRIMARY KEY,
                post_id INTEGER NOT NULL REFERENCES posts(id) ON DELETE CASCADE
            );
            PRAGMA user_version = 2;
        SQL);
    }
}

function now(): string
{
    return date('Y-m-d H:i:s');
}

/** Artículos publicados (fecha de publicación ya cumplida). */
function published_posts(int $limit = 9, int $offset = 0, ?string $category = null): array
{
    $sql = "SELECT * FROM posts WHERE status = 'publicado' AND published_at <= :now";
    $params = [':now' => now()];
    if ($category) {
        $sql .= ' AND category = :cat';
        $params[':cat'] = $category;
    }
    $sql .= ' ORDER BY published_at DESC LIMIT :limit OFFSET :offset';
    $st = db()->prepare($sql);
    foreach ($params as $k => $v) {
        $st->bindValue($k, $v);
    }
    $st->bindValue(':limit', $limit, PDO::PARAM_INT);
    $st->bindValue(':offset', $offset, PDO::PARAM_INT);
    $st->execute();
    return $st->fetchAll();
}

function count_published(?string $category = null): int
{
    $sql = "SELECT COUNT(*) FROM posts WHERE status = 'publicado' AND published_at <= :now";
    $params = [':now' => now()];
    if ($category) {
        $sql .= ' AND category = :cat';
        $params[':cat'] = $category;
    }
    $st = db()->prepare($sql);
    $st->execute($params);
    return (int) $st->fetchColumn();
}

/** Para páginas públicas: si la BD no está disponible, el sitio sigue funcionando sin blog. */
function safe_latest_posts(int $limit): array
{
    try {
        return published_posts($limit);
    } catch (Throwable $e) {
        error_log('[mochcun] blog no disponible: ' . $e->getMessage());
        return [];
    }
}

/** Rutas de las imágenes de portada de un artículo. */
function cover_urls(string $large): array
{
    if ($large === '') {
        return [];
    }
    $base = (string) preg_replace('/-1600\.(webp|jpg)$/', '', $large);
    $ext = str_ends_with($large, '.jpg') ? 'jpg' : 'webp';
    return [
        'large' => $large,
        'medium' => $base . '-800.' . $ext,
        'og' => $base . '-og.jpg',
    ];
}
