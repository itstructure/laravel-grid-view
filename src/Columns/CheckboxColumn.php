<?php

namespace Itstructure\GridView\Columns;

use Itstructure\GridView\Filters\StubFilter;
use Itstructure\GridView\Traits\Configurable;

/**
 * Class CheckboxColumn.
 * @package Itstructure\GridView\Columns
 */
class CheckboxColumn extends BaseColumn
{
    use Configurable;

    /**
     * @var string
     */
    protected $field;

    /**
     * ActionColumn constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->loadConfig($config);

        $this->filter = new StubFilter();
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label ?? trans('grid_view::grid.deletion');
    }

    /**
     * @param $row
     * @return mixed
     */
    public function getValue($row)
    {
        return $row->{$this->attribute} ?? '';
    }

    /**
     * @param $row
     * @return array|string
     */
    public function render($row)
    {
        return view('grid_view::columns.checkbox', [
            'field' => $this->field,
            'value' => $this->getValue($row),
        ])->render();
    }
}
