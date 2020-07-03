<?php

namespace Itstructure\GridView\Interfaces;

/**
 * Interface Formattable
 * @package Itstructure\GridView\Interfaces
 */
interface Formattable
{
    /**
     * @param $value
     * @return mixed
     */
    public function format($value);
}
