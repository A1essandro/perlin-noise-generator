<?php

namespace MapGenerator;

use InvalidArgumentException;
use SplFixedArray;

class PerlinNoiseGenerator
{

    protected $terra;
    protected $persistence;
    protected $size;

    function __construct()
    {

    }

    public function generate()
    {
        $this->initTerra();

        for ($k = 0; $k < $this->getOctaves(); $k++) {
            $this->octave($k);
        }

        return $this->terra;
    }

    protected function octave($octave)
    {
        $freq = pow(2, $octave);
        $amp = pow($this->persistence, $octave);

        $n = $m = $freq + 1;

        $arr = array();
        for ($j = 0; $j < $m; $j++) {
            for ($i = 0; $i < $n; $i++) {
                $arr[$j][$i] = $this->random() * $amp;
            }
        }

        $nx = $this->size / ($n - 1);
        $ny = $this->size / ($m - 1);

        for ($ky = 0; $ky < $this->size; $ky++) {
            for ($kx = 0; $kx < $this->size; $kx++) {
                $i = (int)($kx / $nx);
                $j = (int)($ky / $ny);

                $dx0 = $kx - $i * $nx;
                $dx1 = $nx - $dx0;
                $dy0 = $ky - $j * $ny;
                $dy1 = $ny - $dy0;

                $z = ($arr[$j][$i] * $dx1 * $dy1
                        + $arr[$j][$i + 1] * $dx0 * $dy1
                        + $arr[$j + 1][$i] * $dx1 * $dy0
                        + $arr[$j + 1][$i + 1] * $dx0 * $dy0)
                    / ($nx * $ny);

                $this->terra[$ky][$kx] += $z;
            }
        }
    }

    /**
     * terra array initialization
     */
    protected function initTerra()
    {
        $this->terra = new SplFixedArray($this->size);
        for ($y = 0; $y < $this->size; $y++) {
            $this->terra[$y] = new SplFixedArray($this->size);
            for ($x = 0; $x < $this->size; $x++) {
                $this->terra[$y][$x] = 0;
            }
        }
    }

    /**
     * Getting random float from 0 to 1
     *
     * @return float
     */
    protected function random()
    {
        return (float)rand() / (float)getrandmax();
    }

    protected function getOctaves()
    {
        return (int)log($this->size, 2);
    }

    /**
     * @return array
     */
    public function getSizes()
    {
        return $this->size;
    }

    /**
     * @param int $size
     */
    public function setSize($size)
    {
        if (!is_int($size)) {
            throw new InvalidArgumentException(
                sprintf(
                    "Sizes must be int , %s given", gettype($size)
                )
            );
        }

        $this->size = $size;
    }

    /**
     * @return float
     */
    public function getPersistence()
    {
        return $this->persistence;
    }

    /**
     * @param float $persistence
     */
    public function setPersistence($persistence)
    {
        if (!is_numeric($persistence)) {
            throw new InvalidArgumentException(sprintf("persistence must be numeric, %s given", gettype($persistence)));
        }

        $this->persistence = $persistence;
    }

}