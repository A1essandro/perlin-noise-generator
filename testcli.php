<?php
$start = microtime(true);
ini_set('display_errors', 'On');
require 'src/PerlinNoiseGenerator.php';
$gen = new PerlinNoiseGenerator();

$gen->setPersistence(0.5);
$gen->setSize(500);
print_r($gen->generate());

echo sprintf('Time: %s', round(microtime(true) - $start, 3));