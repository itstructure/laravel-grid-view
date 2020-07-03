<?php

namespace Itstructure\GridView\Columns;

use Closure;
use Itstructure\GridView\Filters\StubFilter;
use Itstructure\GridView\Actions\{BaseAction, View, Edit, Delete};
use Itstructure\GridView\Traits\Configurable;

/**
 * Class ActionColumn.
 * @package Itstructure\GridView\Columns
 */
class ActionColumn extends BaseColumn
{
    use Configurable;

    const
        ACTION_VIEW = 'view',
        ACTION_EDIT = 'edit',
        ACTION_DELETE = 'delete',

        ACTION_DEFINITIONS = [
            self::ACTION_VIEW => View::class,
            self::ACTION_EDIT => Edit::class,
            self::ACTION_DELETE => Delete::class,
        ];

    /**
     * @var array
     */
    protected $actionTypes;

    /**
     * @var BaseAction[]
     */
    protected $actionObjects = [];

    /**
     * ActionColumn constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->loadConfig($config);
        $this->buildActionButtons();

        $this->filter = new StubFilter();
    }

    public function buildActionButtons()
    {
        foreach ($this->actionTypes as $key => $type) {
            if (is_string($key) && in_array($key, array_keys(self::ACTION_DEFINITIONS))) {
                if ($type instanceof Closure) {
                    $class = self::ACTION_DEFINITIONS[$key];
                    $this->fillActionObjects(new $class(['url' => $type]));
                    continue;
                }

                if (is_string($type) && in_array($type, array_keys(self::ACTION_DEFINITIONS))) {
                    $class = self::ACTION_DEFINITIONS[$type];
                    $this->fillActionObjects(new $class);
                    continue;
                }
            }

            if (is_numeric($key)) {
                if (is_string($type) && in_array($type, array_keys(self::ACTION_DEFINITIONS))) {
                    $class = self::ACTION_DEFINITIONS[$type];
                    $this->fillActionObjects(new $class);
                    continue;
                }

                if (is_array($type) && isset($type['class']) && class_exists($type['class'])) {
                    $this->fillActionObjects(new $type['class']($type));
                }
            }
        }
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label ?? trans('grid_view::grid.actions');
    }

    /**
     * @param $row
     * @return string
     */
    public function render($row)
    {
        $value = '';
        $bootstrapColWidth = floor(12 / count($this->actionObjects));
        foreach ($this->actionObjects as $actionObj) {
            $value .= $actionObj->render($row, $bootstrapColWidth);
        }

        return '<div class="row">' . $value . '</div>';
    }

    /**
     * @param BaseAction $actionObject
     */
    protected function fillActionObjects(BaseAction $actionObject): void
    {
        $this->actionObjects = array_merge($this->actionObjects, [$actionObject]);
    }
}
