<?php

declare(strict_types=1);

namespace App\Service;

use App\Config\Config;

final class PriceConverter
{
    private float $rate;

    public function __construct()
    {
        $this->rate = Config::getInstance()->getPrecisionUsd();
    }

    public function toUsd(float $precioArs): float
    {
        return round($precioArs / $this->rate, 2);
    }

    public function getRate(): float
    {
        return $this->rate;
    }
}
