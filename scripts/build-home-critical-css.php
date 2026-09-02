<?php

$root = dirname(__DIR__);

function extractLines(string $file, array $ranges): string
{
    $lines = file($file, FILE_IGNORE_NEW_LINES);
    $out = [];

    foreach ($ranges as [$start, $end]) {
        for ($i = $start - 1; $i < $end && $i < count($lines); $i++) {
            $out[] = $lines[$i];
        }
    }

    return implode("\n", $out) . "\n";
}

$chrome = $root . '/public/sk-assets/css/frontend/site-chrome.css';
$landing = $root . '/public/sk-assets/css/frontend/landing-page.css';

$css = "/* Home critical CSS — above-the-fold header + hero (auto-generated) */\n";
$css .= extractLines($chrome, [
    [1, 359],
    [523, 538],
    [566, 655],
    [669, 693],
    [695, 738],
]);
$css .= extractLines($landing, [
    [8, 33],
    [35, 182],
    [184, 411],
    [418, 450],
    [1738, 1772],
    [2333, 2350],
    [2353, 2365],
    [2484, 2491],
]);

$target = $root . '/public/sk-assets/css/frontend/home-critical.css';
file_put_contents($target, $css);

echo 'Wrote ' . $target . ' (' . strlen($css) . " bytes)\n";
