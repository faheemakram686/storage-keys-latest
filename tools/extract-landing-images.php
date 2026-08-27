<?php
/**
 * Extract inline data-URI images from landing.blade.php into files
 * and replace them with asset URLs.
 */
$blade = dirname(__DIR__) . '/resources/views/ui/pages/landing.blade.php';
$outDir = dirname(__DIR__) . '/public/sk-assets/assets/images/frontend/landing';
if (!is_dir($outDir)) {
    mkdir($outDir, 0755, true);
}

$html = file_get_contents($blade);
$n = 0;

$html = preg_replace_callback(
    '/data:(image\/(?:jpeg|jpg|png|webp|gif));base64,([A-Za-z0-9+\/=]+)/i',
    function ($m) use (&$n, $outDir) {
        $n++;
        $mime = strtolower($m[1]);
        $ext = [
            'image/jpeg' => 'jpg',
            'image/jpg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
        ][$mime] ?? 'bin';

        $bin = base64_decode($m[2], true);
        if ($bin === false) {
            return $m[0];
        }

        $name = sprintf('inline-%02d.%s', $n, $ext);
        $path = $outDir . '/' . $name;
        file_put_contents($path, $bin);

        // Re-encode large JPEGs smaller
        if ($ext === 'jpg' && function_exists('imagecreatefromstring')) {
            $img = @imagecreatefromstring($bin);
            if ($img) {
                $w = imagesx($img);
                $h = imagesy($img);
                $maxW = 1200;
                if ($w > $maxW) {
                    $nw = $maxW;
                    $nh = (int) round($h * $maxW / $w);
                    $dst = imagecreatetruecolor($nw, $nh);
                    imagecopyresampled($dst, $img, 0, 0, 0, 0, $nw, $nh, $w, $h);
                    imagedestroy($img);
                    $img = $dst;
                }
                imagejpeg($img, $path, 75);
                imagedestroy($img);
            }
        }

        return "{{ asset('sk-assets/assets/images/frontend/landing/{$name}') }}";
    },
    $html
);

file_put_contents($blade, $html);
echo "Extracted {$n} images. New blade size: " . filesize($blade) . PHP_EOL;
