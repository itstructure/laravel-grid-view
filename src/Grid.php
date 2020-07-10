<?php

namespace Itstructure\GridView;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Itstructure\GridView\Columns\{BaseColumn, CallbackColumn, DefaultColumn};
use Itstructure\GridView\DataProviders\{BaseDataProvider, EloquentDataProvider};
use Itstructure\GridView\Traits\Configurable;

/**
 * Class Grid
 * @package Itstructure\GridView
 */
class Grid
{
    use Configurable;

    const INIT_PAGE_NUMBER = 1;
    const INIT_ROWS_PER_PAGE = 10;

    /**
     * @var EloquentDataProvider
     */
    protected $dataProvider;

    /**
     * @var bool
     */
    protected $useFilters = true;

    /**
     * @var bool
     */
    protected $strictFilters = false;

    /**
     * @var array|Request|string
     */
    protected $request;

    /**
     * @var LengthAwarePaginator $paginator
     */
    protected $paginator;

    /**
     * @var array
     */
    protected $paginatorOptions = [];

    /**
     * @var int
     */
    protected $page = self::INIT_PAGE_NUMBER;

    /**
     * @var int
     */
    protected $rowsPerPage = self::INIT_ROWS_PER_PAGE;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var array
     */
    protected $columnFields = [];

    /**
     * @var BaseColumn[]
     */
    protected $columnObjects = [];

    /**
     * @var string
     */
    protected $rowsFormAction = '';

    /**
     * @var bool
     */
    protected $useSendButtonAnyway = false;

    /**
     * @var string
     */
    protected $sendButtonLabel;

    /**
     * @var string
     */
    protected $searchButtonLabel;

    /**
     * @var string
     */
    protected $resetButtonLabel;

    /**
     * Grid constructor.
     * @param array $config
     * @throws Exception
     */
    public function __construct(array $config)
    {
        $this->loadConfig($config);
        $this->request = request();

        if (!($this->dataProvider instanceof BaseDataProvider)) {
            throw new Exception('dataProvider must be instance of '.BaseDataProvider::class);
        }
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $this->applyColumnsConfig();

        $this->dataProvider->selectionConditions($this->request, $this->strictFilters);

        $totalCount = $this->dataProvider->getCount();
        $pageNumber = $this->request->get($this->paginatorOptions['pageName'] ?? 'page', $this->page);

        $this->paginator = new LengthAwarePaginator(
            $this->dataProvider->get($this->rowsPerPage, $pageNumber),
            $totalCount,
            $this->rowsPerPage,
            $pageNumber,
            $this->paginatorOptions
        );

        return view('grid_view::grid', [
            'columnObjects' => $this->columnObjects,
            'useFilters' => $this->useFilters,
            'paginator' => $this->paginator,
            'title' => $this->title,
            'rowsFormAction' => $this->rowsFormAction,
            'useSendButtonAnyway' => $this->useSendButtonAnyway,
            'searchButtonLabel' => $this->getSearchButtonLabel(),
            'resetButtonLabel' => $this->getResetButtonLabel(),
            'sendButtonLabel' => $this->getSendButtonLabel()
        ])->render();
    }

    protected function applyColumnsConfig(): void
    {
        foreach ($this->columnFields as $key => $config) {

            $filterSubConfig = $this->useFilters ? [] : ['filter' => false];

            if (is_string($config)) {
                $config = array_merge(['attribute' => $config], $filterSubConfig);
                $this->fillColumnsObjects(new DefaultColumn($config));
                continue;
            }

            if (is_array($config)) {
                $config = array_merge($config, $filterSubConfig);

                if (isset($config['class']) && class_exists($config['class'])) {
                    $this->fillColumnsObjects(new $config['class']($config));
                    continue;
                }

                if (isset($config['value']) && $config['value'] instanceof Closure) {
                    $this->fillColumnsObjects(new CallbackColumn($config));
                    continue;
                }

                $this->fillColumnsObjects(new DefaultColumn($config));
            }
        }
    }

    /**
     * @param BaseColumn $columnObject
     */
    protected function fillColumnsObjects(BaseColumn $columnObject): void
    {
        $this->columnObjects = array_merge($this->columnObjects, [$columnObject]);
    }

    /**
     * @return string
     */
    protected function getSearchButtonLabel(): string
    {
        return $this->searchButtonLabel ?? trans('grid_view::grid.search');
    }

    /**
     * @return string
     */
    protected function getResetButtonLabel(): string
    {
        return $this->resetButtonLabel ?? trans('grid_view::grid.reset');
    }

    /**
     * @return string
     */
    protected function getSendButtonLabel(): string
    {
        return $this->sendButtonLabel ?? trans('grid_view::grid.send');
    }
}
