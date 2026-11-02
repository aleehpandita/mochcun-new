<?php
declare(strict_types=1);

/**
 * Limpia el HTML del editor del blog: deja solo etiquetas y atributos seguros.
 * Todo lo demás se elimina (scripts, estilos, iframes) o se reduce a su texto.
 */
function sanitize_post_html(string $html): string
{
    $html = trim($html);
    if ($html === '') {
        return '';
    }
    $allowed = [
        'p' => [], 'br' => [], 'h2' => [], 'h3' => [], 'h4' => [],
        'strong' => [], 'b' => [], 'em' => [], 'i' => [], 'u' => [], 's' => [],
        'ul' => [], 'ol' => [], 'li' => [], 'blockquote' => [],
        'a' => ['href', 'title', 'target'],
        'img' => ['src', 'alt', 'width', 'height'],
        'figure' => [], 'figcaption' => [],
        'table' => [], 'thead' => [], 'tbody' => [], 'tr' => [], 'th' => [], 'td' => [],
        'hr' => [],
    ];
    $drop = ['script', 'style', 'iframe', 'object', 'embed', 'form', 'input', 'button', 'textarea', 'select', 'svg', 'math', 'template', 'noscript', 'link', 'meta', 'base'];
    $rename = ['h1' => 'h2', 'h5' => 'h4', 'h6' => 'h4', 'div' => 'p'];

    $doc = new DOMDocument('1.0', 'UTF-8');
    libxml_use_internal_errors(true);
    $doc->loadHTML('<?xml encoding="UTF-8"><div id="__root">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NONET);
    libxml_clear_errors();
    $root = $doc->getElementById('__root');
    if (!$root) {
        return '';
    }

    $walk = function (DOMNode $node) use (&$walk, $doc, $allowed, $drop, $rename): void {
        for ($child = $node->firstChild; $child !== null; $child = $next) {
            $next = $child->nextSibling;
            if ($child instanceof DOMComment || $child instanceof DOMProcessingInstruction) {
                $node->removeChild($child);
                continue;
            }
            if ($child instanceof DOMText) {
                $child->nodeValue = str_replace("\u{00A0}", ' ', (string) $child->nodeValue);
                continue;
            }
            if (!$child instanceof DOMElement) {
                $node->removeChild($child);
                continue;
            }
            $tag = strtolower($child->tagName);
            if (in_array($tag, $drop, true)) {
                $node->removeChild($child);
                continue;
            }
            if (isset($rename[$tag])) {
                $new = $doc->createElement($rename[$tag]);
                while ($child->firstChild) {
                    $new->appendChild($child->firstChild);
                }
                $node->replaceChild($new, $child);
                $child = $new;
                $tag = $rename[$tag];
            }
            if (!isset($allowed[$tag])) {
                // Etiqueta no permitida (span, font…): se conserva solo su contenido
                $walk($child);
                while ($child->firstChild) {
                    $node->insertBefore($child->firstChild, $child);
                }
                $node->removeChild($child);
                continue;
            }
            foreach (iterator_to_array($child->attributes) as $attr) {
                if (!in_array(strtolower($attr->name), $allowed[$tag], true)) {
                    $child->removeAttribute($attr->name);
                }
            }
            if ($tag === 'a') {
                $href = trim($child->getAttribute('href'));
                if (!preg_match('#^(https?://|mailto:|tel:|/|\#)#i', $href)) {
                    $child->removeAttribute('href');
                } else {
                    $child->setAttribute('href', $href);
                }
                $external = (bool) preg_match('#^https?://#i', $href) && !str_contains($href, (string) parse_url((string) config('site_url'), PHP_URL_HOST));
                if ($external) {
                    $child->setAttribute('target', '_blank');
                    $child->setAttribute('rel', 'noopener');
                } else {
                    $child->removeAttribute('target');
                }
            }
            if ($tag === 'img') {
                $src = trim($child->getAttribute('src'));
                if (!preg_match('#^/uploads/blog/[A-Za-z0-9/_.-]+\.(webp|jpe?g|png|gif)$#', $src)) {
                    $node->removeChild($child);
                    continue;
                }
                $dim = @getimagesize(ROOT . $src);
                if ($dim) {
                    $child->setAttribute('width', (string) $dim[0]);
                    $child->setAttribute('height', (string) $dim[1]);
                }
                $child->setAttribute('loading', 'lazy');
                $child->setAttribute('decoding', 'async');
                if (!$child->hasAttribute('alt')) {
                    $child->setAttribute('alt', '');
                }
            }
            $walk($child);
            // Párrafos vacíos (<p><br></p>) que deja el editor
            if (in_array($tag, ['p', 'h2', 'h3', 'h4', 'li', 'blockquote'], true) && trim($child->textContent) === '' && $child->getElementsByTagName('img')->length === 0) {
                $node->removeChild($child);
            }
        }
    };
    $walk($root);

    $out = '';
    foreach ($root->childNodes as $n) {
        $out .= $doc->saveHTML($n);
    }
    return trim($out);
}
