<?php

use MapGenerator\PerlinNoiseGenerator;

require_once dirname(__FILE__) . '/../vendor/autoload.php';


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

    #endregion

    #region Tests

    /**
     * @dataProvider providerSetSize
     */
    public function testSetSize($count)
    {
        $this->perlinNoiseGenerator->setPersistence(0.5);
        $this->perlinNoiseGenerator->setSize($count);

        $this->assertEquals($count, count($this->perlinNoiseGenerator->generate()));
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

    #endregion

}
