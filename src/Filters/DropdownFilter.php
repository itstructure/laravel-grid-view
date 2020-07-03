<?php

namespace Itstructure\GridView\Filters;

/**
 * Class DropdownFilter.
 * @package Itstructure\GridView\Filters
 */
class DropdownFilter extends BaseFilter
{
    /**
     * @return string
     */
    public function render(): string
    {
        return view('grid_view::filters.dropdown', [
            'name' => $this->getName(),
            'value' => $this->getValue(),
            'data' => $this->getData()
        ]);
    }
}
