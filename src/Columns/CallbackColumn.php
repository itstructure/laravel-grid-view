<?php

namespace Itstructure\GridView\Columns;

/**
 * Class CallbackColumn.
 * @package Itstructure\GridView\Columns
 */
class CallbackColumn extends BaseColumn
{
    /**
     * @param $row
     * @return mixed
     */
    public function getValue($row)
    {
        return call_user_func($this->value, $row) ?? '';
    }
}
