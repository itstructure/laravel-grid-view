<?php

namespace Itstructure\GridView\Helpers;

use Itstructure\GridView\Columns\ActionColumn;
use Itstructure\GridView\Columns\BaseColumn;
use Illuminate\Http\Request;

/**
 * Class SortHelper
 * @package Itstructure\GridView\Helpers
 */
class SortHelper
{
    const SORT_ASC = 'asc';
    const SORT_DESC = 'desc';

    /**
     * Build sort link for model entry.
     * @param Request $request
     * @param BaseColumn|ActionColumn $column_obj
     * @return string
     */
    public static function getSortableLink(Request $request, $column_obj): string
    {
        if ($column_obj->getSort() === false || $column_obj instanceof ActionColumn) {
            return '';
        }

        $sortQuery = $request->get('sort', null);

        if (is_null($sortQuery)) {
            return $request->fullUrlWithQuery([
                'sort' => $column_obj->getSort(),
            ]);
        }

        if ($sortQuery == $column_obj->getSort()) {
            return $request->fullUrlWithQuery([
                'sort' => '-' . $column_obj->getSort(),
            ]);
        }

        if ($sortQuery == ('-' . $column_obj->getSort())) {
            return $request->fullUrlWithQuery([
                'sort' => $column_obj->getSort(),
            ]);
        }

        return $request->fullUrlWithQuery([
            'sort' => $column_obj->getSort(),
        ]);
    }

    /**
     * @param Request $request
     * @return string
     */
    public static function getSortColumn(Request $request): string
    {
        $column = $request->get('sort');

        return str_replace('-', '', $column);
    }

    /**
     * @param Request $request
     * @return string
     */
    public static function getDirection(Request $request): string
    {
        $pos = mb_strpos($request->get('sort'), '-');
        if ($pos === 0) {
            return self::SORT_DESC;
        }

        return self::SORT_ASC;
    }
}
