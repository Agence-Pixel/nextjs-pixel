<?php

if (!$attributes['svgURL']) {
    $attributes['svgURL'] = get_theme_file_uri('/assets/images/g.svg');
}

$content = file_get_contents($attributes['svgURL']);

echo str_replace('<svg', '<svg class="svg-block"', $content);