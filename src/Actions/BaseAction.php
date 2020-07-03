<?php

namespace Itstructure\GridView\Actions;

use Closure;
use Itstructure\GridView\Traits\{Configurable, Attributable};

/**
 * Class BaseAction.
 * @package Itstructure\GridView\Actions
 *
 * @property string $url
 */
abstract class BaseAction
{
    use Configurable, Attributable;

    const BOOTSTRAP_COL_WIDTH = 4;

    /**
     * @var string|Closure|null
     */
    protected $url;

    /**
     * BaseButton constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->loadConfig($config);
    }

    /**
     * @param $row
     * @param int $bootstrapColWidth
     * @return mixed
     */
    abstract public function render($row, int $bootstrapColWidth = self::BOOTSTRAP_COL_WIDTH);

    /**
     * Build url for some actions.
     * @param $row
     */
    abstract protected function buildUrl($row);

    /**
     * @param $row
     * @return mixed|string
     */
    protected function getUrl($row)
    {
        if ($this->url instanceof Closure) {
            return call_user_func($this->url, $row);

        } else if (!is_null($this->url) && is_string($this->url)) {
            return $this->url;
        }

        return $this->buildUrl($row);
    }

    /**
     * @return string
     */
    protected function getCurrentUrl()
    {
        return url()->current();
    }
}
