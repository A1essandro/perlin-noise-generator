<?php

use MapGenerator\PerlinNoiseGenerator;

require_once __DIR__ . '/../vendor/autoload.php';


class PerlinNoiseGeneratorTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var PerlinNoiseGenerator|null
     */
    protected $perlinNoiseGenerator;

    protected function setUp()
    {
        $this->perlinNoiseGenerator = new PerlinNoiseGenerator();
    }

    protected function tearDown()
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
     * @expectedException InvalidArgumentException
     */
    public function testSetSizeNotInt($sizeToSet)
    {
        $this->perlinNoiseGenerator->setSize($sizeToSet);
    }

    /**
     * @dataProvider providerSetInvalidMapSeed
     * @expectedException InvalidArgumentException
     */
    public function testSetInvalidMapSeed($seed)
    {
        $this->perlinNoiseGenerator->setSize($seed);
    }

    /**
     * @dataProvider providerSetInvalidPersistence
     * @expectedException InvalidArgumentException
     */
    public function testSetInvalidPersistence($persistence)
    {
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

    /**
     * @expectedException LogicException
     */
    public function testGenerationWithoutPersistence()
    {
        $this->perlinNoiseGenerator->setSize(30);
        $this->perlinNoiseGenerator->generate();
    }

    /**
     * @expectedException LogicException
     */
    public function testGenerationWithoutSize()
    {
        $this->perlinNoiseGenerator->setPersistence(0.5);
        $this->perlinNoiseGenerator->generate();
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
