<?php
$start = microtime(true);
ini_set('display_errors', 'On');
require 'src/PerlinNoiseGenerator.php';
$gen = new MapGenerator\PerlinNoiseGenerator();

$gen->setPersistence(0.5);
$gen->setSize(750);
$gen->generate();

echo sprintf('Time: %s', round(microtime(true) - $start, 3));