<?php
require dirname(__DIR__) . '/app/bootstrap.php';

header('Content-Type: application/rss+xml; charset=UTF-8');
$posts = safe_latest_posts(20);
$x = fn (string $s) => htmlspecialchars($s, ENT_XML1 | ENT_QUOTES, 'UTF-8');
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
  <title>Blog MOCHCUN</title>
  <link><?= $x(url('/blog/')) ?></link>
  <atom:link href="<?= $x(url('/blog/feed/')) ?>" rel="self" type="application/rss+xml"/>
  <description>Guías de verificación y trámites federales para transportistas en Cancún.</description>
  <language>es-mx</language>
<?php foreach ($posts as $p): $link = url('/blog/' . $p['slug'] . '/'); ?>
  <item>
    <title><?= $x($p['title']) ?></title>
    <link><?= $x($link) ?></link>
    <guid isPermaLink="true"><?= $x($link) ?></guid>
    <pubDate><?= date(DATE_RSS, strtotime((string) $p['published_at'])) ?></pubDate>
    <category><?= $x(category_name($p['category'])) ?></category>
    <description><?= $x($p['description'] ?: plain_excerpt((string) $p['content'], 300)) ?></description>
  </item>
<?php endforeach; ?>
</channel>
</rss>
