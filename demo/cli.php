<?php
$start = microtime(true);
ini_set('display_errors', 'On');
require_once __DIR__ . '/../src/PerlinNoiseGenerator.php';
$gen = new MapGenerator\PerlinNoiseGenerator();

$memStart = memory_get_usage();
$gen->setPersistence(0.5);
$gen->setSize(1025);
$gen->setMapSeed('seed');
$gen->generate();

echo sprintf('Memory Peak Usage: %sMB', round(memory_get_peak_usage() / 1024 / 1024, 2)) . PHP_EOL;
echo sprintf('Memory Usage: %sMB', round((memory_get_usage() - $memStart) / 1024 / 1024, 2)) . PHP_EOL;
echo sprintf('Time: %s', round(microtime(true) - $start, 3)) . PHP_EOL;