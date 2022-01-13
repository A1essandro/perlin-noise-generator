<?php

use MapGenerator\PerlinNoiseGenerator;

class PerlinNoiseGeneratorTest extends PHPUnit\Framework\TestCase
{

    /**
     * @var PerlinNoiseGenerator|null
     */
    protected $perlinNoiseGenerator;

    protected function setUp(): void
    {
        $this->perlinNoiseGenerator = new PerlinNoiseGenerator();
    }

    protected function tearDown(): void
    {
        $this->perlinNoiseGenerator = null;
    }

    #region DataProviders

    public function providerSetSize()
    {
        return array(
            array(2),
            array(10),
            array(100)
        );
    }

    public function providerSetSizeNotInt()
    {
        return array(
            array('a'),
            array(2.1),
            array(10.)
        );
    }

    public function providerSetInvalidPersistence()
    {
        return array(
            array('a'),
            array(null),
            array(array())
        );
    }

    public function providerSetInvalidMapSeed()
    {
        return array(
            array(array()),
            array(null),
            array(new StdClass())
        );
    }

    #endregion
    #region Tests

    /**
     * @dataProvider providerSetSize
     */
    public function testSize($count)
    {
        $this->perlinNoiseGenerator->setPersistence(0.5);
        $this->perlinNoiseGenerator->setSize($count);
        $map = $this->perlinNoiseGenerator->generate();

        $this->assertEquals($count, count($map));
        $this->assertEquals($this->perlinNoiseGenerator->getSizes(), count($map));
        $this->assertEquals(pow($count, 2), count(self::expandMap($map)));
    }

    /**
     * @dataProvider providerSetSizeNotInt
     */
    public function testSetSizeNotInt($sizeToSet)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->perlinNoiseGenerator->setSize($sizeToSet);
    }

    /**
     * @dataProvider providerSetInvalidMapSeed
     */
    public function testSetInvalidMapSeed($seed)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->perlinNoiseGenerator->setMapSeed($seed);
    }

    /**
     * @dataProvider providerSetInvalidPersistence
     */
    public function testSetInvalidPersistence($persistence)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->perlinNoiseGenerator->setPersistence($persistence);
    }

    public function testContains()
    {
        $this->perlinNoiseGenerator->setSize(10);
        $this->perlinNoiseGenerator->setPersistence(0.5);
        $map = $this->perlinNoiseGenerator->generate();

        $points = array();
        foreach ($map as $line) {
            foreach ($line as $point) {
                $points[] = $point;
            }
        }

        $this->assertContainsOnly('float', $points);
    }

    public function testMapSeed()
    {
        $mapHash1 = uniqid() . '1';
        $mapHash2 = uniqid() . '2';

        $this->perlinNoiseGenerator->setSize(30);
        $this->perlinNoiseGenerator->setPersistence(0.77);

        $this->perlinNoiseGenerator->setMapSeed($mapHash1);
        $map1 = $this->perlinNoiseGenerator->generate();
        $this->perlinNoiseGenerator->setMapSeed($mapHash2);
        $map2 = $this->perlinNoiseGenerator->generate();

        $this->assertNotEquals(self::expandMap($map1), self::expandMap($map2));

        $mapSeed = uniqid();
        $this->perlinNoiseGenerator->setMapSeed($mapSeed);
        $map1 = $this->perlinNoiseGenerator->generate();
        $this->perlinNoiseGenerator->setMapSeed($mapSeed);
        $map2 = $this->perlinNoiseGenerator->generate();

        $this->assertEquals($mapSeed, $this->perlinNoiseGenerator->getMapSeed());
        $this->assertEquals(self::expandMap($map1), self::expandMap($map2));
    }

    public function testGenerationWithoutPersistence()
    {
        $this->expectException(LogicException::class);
        $this->perlinNoiseGenerator->setSize(30);
        $this->perlinNoiseGenerator->generate();
    }

    public function testGenerationWithoutSize()
    {
        $this->expectException(LogicException::class);
        $this->perlinNoiseGenerator->setPersistence(0.5);
        $this->perlinNoiseGenerator->generate();
    }

    public function testGenerationWithOptions()
    {
        $noise = $this->perlinNoiseGenerator->generate(array(
            PerlinNoiseGenerator::SIZE => 100,
            PerlinNoiseGenerator::PERSISTENCE => 0.756,
            PerlinNoiseGenerator::MAP_SEED => microtime()
        ));

        $this->assertInstanceOf(SplFixedArray::class, $noise);
    }

    public function testMixedOptionsGeneration()
    {
        $this->perlinNoiseGenerator->setSize(100);
        $noise = $this->perlinNoiseGenerator->generate(array(
            PerlinNoiseGenerator::PERSISTENCE => 0.756,
            PerlinNoiseGenerator::MAP_SEED => microtime()
        ));

        $this->assertInstanceOf(SplFixedArray::class, $noise);
    }

    public function testGenerationViaOptionsWithoutSize()
    {
        $this->expectException(LogicException::class);
        $this->perlinNoiseGenerator->generate(array(
            PerlinNoiseGenerator::PERSISTENCE => 0.756,
            PerlinNoiseGenerator::MAP_SEED => microtime()
        ));
    }

    #endregion

    private static function expandMap($map)
    {
        $expandPoints = array();
        foreach ($map as $line) {
            foreach ($line as $point) {
                $expandPoints[] = $point;
            }
        }

        return $expandPoints;
    }

}
