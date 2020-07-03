<?php

namespace Itstructure\GridView\Filters;

/**
 * Class TextFilter.
 * @package Itstructure\GridView\Filters
 */
class TextFilter extends BaseFilter
{
    /**
     * @return string
     */
    public function render(): string
    {
        return view('grid_view::filters.text', [
            'name' => $this->getName(),
            'value' => $this->getValue(),
        ]);
    }
}
