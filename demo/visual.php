<?php

ini_set('display_errors', 'On');
require '../src/PerlinNoiseGenerator.php';
$gen = new MapGenerator\PerlinNoiseGenerator();

$size = 250;

$gen->setPersistence(0.78);
$gen->setSize($size);
$map = $gen->generate();

$image = imagecreatetruecolor($size, $size);

$max = 0;
$min = PHP_INT_MAX;
for ($iy = 0; $iy < $size; $iy++) {
    for ($ix = 0; $ix < $size; $ix++) {
        $h = $map[$iy][$ix];
        if ($min > $h) {
            $min = $h;
        }
        if ($max < $h) {
            $max = $h;
        }
    }
}
$diff = $max - $min;

for ($iy = 0; $iy < $size; $iy++) {
    for ($ix = 0; $ix < $size; $ix++) {
        $h = 255 * ($map[$iy][$ix] - $min) / $diff;
        $color = imagecolorallocate($image, $h, $h, $h);
        imagesetpixel($image, $ix, $iy, $color);
    }
}

imagepng($image, 'visual.png');
imagedestroy($image);