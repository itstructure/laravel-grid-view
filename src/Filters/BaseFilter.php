<?php

namespace Itstructure\GridView\Filters;

use Itstructure\GridView\Traits\Configurable;

/**
 * Class BaseFilter.
 * @package Itstructure\GridView\Filters
 */
abstract class BaseFilter
{
    use Configurable;

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var string
     */
    protected $value;

    /**
     * @var mixed
     */
    protected $data = [];

    /**
     * BaseFilter constructor.
     * @param $config
     */
    public function __construct($config = [])
    {
        $this->loadConfig($config);
        $this->setValue();
    }

    /**
     * Render filters template.
     */
    abstract public function render(): string;

    public function setValue(): void
    {
        $this->value = request()->input('filters.' . $this->getName(), $this->value);
    }

    /**
     * @return mixed
     */
    protected function getValue()
    {
        return $this->value ?? null;
    }

    /**
     * @return string
     */
    protected function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    protected function getData()
    {
        return $this->data;
    }
}
