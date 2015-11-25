<?php

ini_set('display_errors', 'On');
require 'src/PerlinNoiseGenerator.php';
$gen = new PerlinNoiseGenerator();

$x = 500;
$y = 500;

$gen->setPersistence(0.78);
$gen->setSizes(array($x, $y));
$map = $gen->generate();

$image = imagecreatetruecolor($x, $y);

for ($iy = 0; $iy < $y; $iy++) {
    for ($ix = 0; $ix < $x; $ix++) {
        $h = $map[$ix][$iy];
        $color = imagecolorallocate($image, $h * 50, $h * 50, $h * 50);
        imagesetpixel($image, $ix, $iy, $color);
    }
}

imagepng($image, 'visual.png');
imagedestroy($image);