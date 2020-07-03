<?php

namespace Itstructure\GridView\Actions;

/**
 * Class Edit.
 * @package Itstructure\GridView\Actions
 */
class Edit extends BaseAction
{
    const ACTION = 'edit';

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
