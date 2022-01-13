<?php

namespace MapGenerator;

use InvalidArgumentException;
use LogicException;
use SplFixedArray;

class PerlinNoiseGenerator
{

    /**
     * @var \SplFixedArray[]
     */
    protected $terra;
    protected $persistence;
    protected $size;

    const SIZE = 'size';
    const PERSISTENCE = 'persistence';
    const MAP_SEED = 'map_seed';

    /**
     * @var number|string
     */
    protected $mapSeed;

    /**
     * @var number
     */
    protected $numericMapSeed;

    /**
     * @return number|string
     */
    public function getMapSeed()
    {
        return $this->mapSeed;
    }

    /**
     * @param number|string $mapSeed
     */
    public function setMapSeed($mapSeed): void
    {
        if (!is_numeric($mapSeed) && !is_string($mapSeed)) {
            throw new InvalidArgumentException(
                sprintf("mapSeed must be string or numeric, %s given", gettype($mapSeed))
            );
        }

        $this->mapSeed = $mapSeed;

        $this->numericMapSeed = is_numeric($mapSeed)
            ? $mapSeed
            : intval(substr(md5($mapSeed), -8), 16);
    }

    /**
     * @param array $options
     *
     * @return \SplFixedArray[]
     */
    public function generate(array $options = []): SplFixedArray
    {
        $this->setOptions($options);
        $this->initTerra();

        for ($k = 0; $k < $this->getOctaves(); $k++) {
            $this->octave($k);
        }

        return $this->terra;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options): void
    {
        if (array_key_exists(self::MAP_SEED, $options)) {
            $this->setMapSeed($options[self::MAP_SEED]);
        }

        if (array_key_exists(self::SIZE, $options)) {
            $this->setSize($options[self::SIZE]);
        }

        if (array_key_exists(self::PERSISTENCE, $options)) {
            $this->setPersistence($options[self::PERSISTENCE]);
        }
    }

    /*
     * /!\ edge effet on this
     */
    protected function octave(int $octave): void
    {
        $freq = pow(2, $octave);
        $amp = pow($this->persistence, $octave);

        $n = $m = $freq + 1;

        $arr = [];
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
    protected function initTerra(): void
    {
        if (empty($this->mapSeed)) {
            $this->setMapSeed(microtime(true));
        }

        if (!$this->getPersistence()) {
            throw new LogicException('Persistence must be set');
        }

        if (!$this->getSize()) {
            throw new LogicException('Size must be set');
        }

        mt_srand($this->numericMapSeed * $this->persistence * $this->size);

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
    protected function random(): float
    {
        return mt_rand() / getrandmax();
    }

    protected function getOctaves(): int
    {
        return (int)log($this->size, 2);
    }

    /**
     * @deprecated
     * @return int
     */
    public function getSizes(): int
    {
        return $this->getSize();
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param int $size
     */
    public function setSize(int $size): void
    {
        if ($size <= 0) {
            throw new InvalidArgumentException("Positive integers only");
        }

        $this->size = $size;
    }

    /**
     * @return float
     */
    public function getPersistence(): float
    {
        return $this->persistence;
    }

    /**
     * @param float $persistence
     */
    public function setPersistence(float $persistence): void
    {
        $this->persistence = $persistence;
    }

}