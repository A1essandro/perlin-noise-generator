<?php

class PerlinNoiseGenerator
{

    protected $terra;
    protected $persistence;
    protected $sizes;

    function __construct()
    {

    }

    public function generate()
    {
        $this->terra = new SplFixedArray($this->sizes[1]);
        for ($y = 0; $y < $this->sizes[1]; $y++) {
            $this->terra[$y] = new SplFixedArray($this->sizes[0]);
        }

        for ($k = 0; $k < $this->getOctaves(); $k++) {
            $freq = pow(2, $k);
            $amp = pow($this->persistence, $k);

            $n = $m = $freq + 1;

            $arr = array();
            for ($j = 0; $j < $m; $j++) {
                for ($i = 0; $i < $n; $i++) {
                    $arr[$j][$i] = $this->random() * $amp;
                }
            }

            $nx = $this->sizes[0] / ($n - 1.0);
            $ny = $this->sizes[1] / ($m - 1.0);

            for ($ky = 0; $ky < $this->sizes[1]; $ky++) {
                for ($kx = 0; $kx < $this->sizes[0]; $kx++) {
                    $i = (int)($kx / $nx);
                    $j = (int)($ky / $ny);

                    $dx0 = $kx - $i * $nx;
                    $dx1 = $nx - $dx0;
                    $dy0 = $ky - $j * $ny;
                    $dy1 = $ny - $dy0;

                    $z = $arr[$j][$i] * $dx1 * $dy1;
                    $z += $arr[$j][$i + 1] * $dx0 * $dy1;
                    $z += $arr[$j + 1][$i] * $dx1 * $dy0;
                    $z += $arr[$j + 1][$i + 1] * $dx0 * $dy0;
                    $z /= $nx * $ny;

                    if ($this->terra[$ky][$kx] === null) {
                        $this->terra[$ky][$kx] = 0;
                    }
                    $this->terra[$ky][$kx] += $z;
                }
            }
        }

        return $this->terra;
    }

    protected function getOctaves()
    {
        return (int)log(max($this->sizes), 2.0);
    }

    /**
     * @return array
     */
    public function getSizes()
    {
        return $this->sizes;
    }

    /**
     * @param array $sizes
     */
    public function setSizes(array $sizes)
    {
        if (count($sizes) != 2 || !is_int($sizes[0]) || !is_int($sizes[1])) {
            throw new InvalidArgumentException(sprintf(
                "Sizes must be array with two int elements (keys 0 and 1), %s given",
                print_r($sizes, 1)));
        }

        $this->sizes = $sizes;
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

    function random()
    {
        return (float)rand() / (float)getrandmax();
    }
}