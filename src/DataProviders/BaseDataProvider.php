<?php

namespace Itstructure\GridView\DataProviders;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Class BaseDataProvider
 * @package Itstructure\GridView\DataProviders
 */
abstract class BaseDataProvider
{
    /**
     * @param int $perPage
     * @param int $page
     * @return Collection
     */
    abstract public function get(int $perPage, int $page): Collection;

    /**
     * @param Request $request
     * @param bool $strictFilters
     * @return void
     */
    abstract public function selectionConditions(Request $request, bool $strictFilters = false): void;

    /**
     * @return int
     */
    abstract public function getCount(): int;
}
