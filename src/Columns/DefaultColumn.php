<?php

namespace Itstructure\GridView\Columns;

/**
 * Class DefaultColumn
 * @package Itstructure\GridView\Columns
 */
class DefaultColumn extends BaseColumn
{
    /**
     * @param $row
     * @return mixed
     */
    public function getValue($row)
    {
        return $row->{$this->attribute} ?? '';
    }
}
