<?php
$path = dirname(__DIR__) . '/public/sk-assets/css/frontend/font-icons.css';
$css = file_get_contents($path);
$css = preg_replace('/@font-face\s*\{/', "@font-face {\n    font-display: swap;", $css);
// Avoid double-adding
$css = preg_replace('/font-display:\s*swap;\s*font-display:\s*swap;/', 'font-display: swap;', $css);
file_put_contents($path, $css);
echo substr_count($css, 'font-display: swap') . " font-display rules\n";
