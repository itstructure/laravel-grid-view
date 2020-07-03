<?php

namespace Itstructure\GridView\Helpers;

use Illuminate\Http\Request;
use Illuminate\Pagination\AbstractPaginator as Paginator;

/**
 * Class UrlSliderHelper
 * @package Itstructure\GridView\Helpers
 */
class UrlSliderHelper
{
    /**
     * @param Request $request
     * @param Paginator $paginator
     * @return string
     */
    public static function previousPageUrl(Request $request, Paginator $paginator): string
    {
        return static::toPageUrl($request, $paginator, $paginator->currentPage() - 1);
    }

    /**
     * @param Request $request
     * @param Paginator $paginator
     * @return string
     */
    public static function nextPageUrl(Request $request, Paginator $paginator): string
    {
        return static::toPageUrl($request, $paginator, $paginator->currentPage() + 1);
    }

    /**
     * @param Request $request
     * @param Paginator $paginator
     * @param int $page
     * @return string
     */
    public static function toPageUrl(Request $request, Paginator $paginator, int $page = 1): string
    {
        return $request->fullUrlWithQuery([$paginator->getPageName() => $page]);
    }
}
