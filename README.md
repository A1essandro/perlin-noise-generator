# perlin-noise-generator

##Description
Heightmaps generator on PHP using perlin-noise algorithm.

##Requirements
This package is only supported on PHP 5.3 and above.

##Installing
###Composer
See more [getcomposer.org](http://getcomposer.org).

Execute command 
```
composer require a1essandro/perlin-noise-generator dev-master
```

##Usage

```php
$generator = new MapGenerator\PerlinNoiseGenerator();
$generator->setSize(100) //heightmap size: 100x100
$generator->setPersistence(0.8) //map roughness
$map = $generator->generatr();
```
