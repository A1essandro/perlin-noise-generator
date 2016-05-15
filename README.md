# perlin-noise-generator

[![Build Status](https://travis-ci.org/A1essandro/perlin-noise-generator.svg)](https://travis-ci.org/A1essandro/perlin-noise-generator) [![Coverage Status](https://coveralls.io/repos/A1essandro/perlin-noise-generator/badge.svg?branch=master&service=github)](https://coveralls.io/github/A1essandro/perlin-noise-generator?branch=master) [![Latest Stable Version](https://poser.pugx.org/a1essandro/perlin-noise/v/stable)](https://packagist.org/packages/a1essandro/perlin-noise) [![Total Downloads](https://poser.pugx.org/a1essandro/perlin-noise/downloads)](https://packagist.org/packages/a1essandro/perlin-noise) [![Latest Unstable Version](https://poser.pugx.org/a1essandro/perlin-noise/v/unstable)](https://packagist.org/packages/a1essandro/perlin-noise) [![License](https://poser.pugx.org/a1essandro/perlin-noise/license)](https://packagist.org/packages/a1essandro/perlin-noise)

##Description
Heightmaps generator on PHP using perlin-noise algorithm.

See also [Diamond-Square algorithm](https://github.com/A1essandro/Diamond-And-Square) with the similar API.

##Requirements
This package is only supported on PHP 5.3 and above.

##Installing
###Composer
See more [getcomposer.org](http://getcomposer.org).

Execute command 
```
composer require a1essandro/perlin-noise ~1.0
```

##Usage

```php
$generator = new MapGenerator\PerlinNoiseGenerator();
$generator->setSize(100); //heightmap size: 100x100
$generator->setPersistence(0.8); //map roughness
$generator->setMapSeed('value'); //optional
$map = $generator->generate();
```

####or

```php
$generator = new MapGenerator\PerlinNoiseGenerator();
$map = $generator->generate([
    PerlinNoiseGenerator::SIZE => 100,
    PerlinNoiseGenerator::PERSISTENCE => 0.8,
    PerlinNoiseGenerator::MAP_SEED => 'value'
]);
```

####mixed:

```php
$generator = new MapGenerator\PerlinNoiseGenerator();
$generator->setSize(100);
$map = $generator->generate([
    PerlinNoiseGenerator::PERSISTENCE => 0.8
]);
```