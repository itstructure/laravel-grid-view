<?php

namespace Itstructure\GridView\Filters;

/**
 * Class StubFilter.
 * @package Itstructure\GridView\Filters
 */
class StubFilter extends BaseFilter
{
    /**
     * @return string
     */
    public function render(): string
    {
        return view('grid_view::filters.stub');
    }
}
