<?php

namespace Itstructure\GridView\Formatters;

use Itstructure\GridView\Interfaces\Formattable;
use Itstructure\GridView\Traits\{Configurable, Attributable};

/**
 * Class BaseFormatter
 * @package Itstructure\GridView\Formatters
 */
abstract class BaseFormatter implements Formattable
{
    use Configurable, Attributable;

    /**
     * BaseFormatter constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->loadConfig($config);
    }
}
