<?php
$src = __DIR__ . '/../public/sk-assets/assets/images/frontend/landing-hero.jpg';
$bak = __DIR__ . '/../public/sk-assets/assets/images/frontend/landing-hero.orig.jpg';
if (!file_exists($bak)) {
    copy($src, $bak);
}
$img = imagecreatefromjpeg($src);
$w = imagesx($img);
$h = imagesy($img);
$maxW = 1600;
if ($w > $maxW) {
    $nw = $maxW;
    $nh = (int) round($h * $maxW / $w);
    $dst = imagecreatetruecolor($nw, $nh);
    imagecopyresampled($dst, $img, 0, 0, 0, 0, $nw, $nh, $w, $h);
    imagedestroy($img);
    $img = $dst;
    $w = $nw;
    $h = $nh;
}
imagejpeg($img, $src, 72);
$out = ['jpg' => filesize($src), 'w' => $w, 'h' => $h];
if (function_exists('imagewebp')) {
    $webp = __DIR__ . '/../public/sk-assets/assets/images/frontend/landing-hero.webp';
    imagewebp($img, $webp, 75);
    $out['webp'] = filesize($webp);
}
imagedestroy($img);
echo json_encode($out), PHP_EOL;
