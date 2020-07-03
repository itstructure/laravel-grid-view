<?php

namespace Itstructure\GridView\Actions;

/**
 * Class View.
 * @package Itstructure\GridView\Actions
 */
class View extends BaseAction
{
    const ACTION = 'view';

    /**
     * @param $row
     * @param int $bootstrapColWidth
     * @return array|string
     */
    public function render($row, int $bootstrapColWidth = BaseAction::BOOTSTRAP_COL_WIDTH)
    {
        return view('grid_view::actions.'.self::ACTION, [
            'url' => $this->getUrl($row),
            'bootstrapColWidth' => $bootstrapColWidth,
            'htmlAttributes' => $this->buildHtmlAttributes()
        ])->render();
    }

    /**
     * @param $row
     * @return string
     */
    protected function buildUrl($row)
    {
        return $this->getCurrentUrl() . '/' . $row->id . '/' . self::ACTION;
    }
}
