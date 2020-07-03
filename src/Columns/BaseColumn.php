<?php

namespace Itstructure\GridView\Columns;

use Exception;
use Itstructure\GridView\Filters\{BaseFilter, StubFilter, TextFilter};
use Itstructure\GridView\Formatters\{HtmlFormatter, ImageFormatter, TextFormatter, UrlFormatter};
use Itstructure\GridView\Interfaces\Formattable;
use Itstructure\GridView\Traits\{Configurable, Attributable};

/**
 * Class BaseColumn
 * @package Itstructure\GridView\Columns
 */
abstract class BaseColumn
{
    use Configurable, Attributable;

    const
        FORMATTER_HTML = 'html',
        FORMATTER_IMAGE = 'image',
        FORMATTER_TEXT = 'text',
        FORMATTER_URL = 'url',

        FORMATTER_DEFINITIONS = [
            self::FORMATTER_HTML => HtmlFormatter::class,
            self::FORMATTER_IMAGE => ImageFormatter::class,
            self::FORMATTER_TEXT => TextFormatter::class,
            self::FORMATTER_URL => UrlFormatter::class,
        ];

    /**
     * @var string
     */
    protected $label;

    /**
     * @var string
     */
    protected $attribute;

    /**
     * @var string $value
     */
    protected $value;

    /**
     * @var bool|null|string|BaseFilter
     */
    protected $filter;

    /**
     * @var string|Formattable
     */
    protected $format;

    /**
     * BaseColumn constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->loadConfig($config);
        $this->buildFilter();
        $this->buildFormatter();
    }

    /**
     * @param $row
     * @return mixed
     */
    public function getValue($row)
    {
    }

    /**
     * Render row attribute value.
     * @param $row
     * @return mixed
     */
    public function render($row)
    {
        return $this->formatTo($this->getValue($row));
    }

    /**
     * Format value with formatter.
     * @param $value
     * @return mixed
     */
    public function formatTo($value)
    {
        return $this->format->format($value);
    }

    /**
     * Get title for grid head.
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label ?? ucfirst($this->attribute);
    }

    /**
     * Get attribute.
     * @return string|null
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * @return BaseFilter
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @param BaseFilter $filter
     */
    protected function setFilter(BaseFilter $filter): void
    {
        $this->filter = $filter;
    }

    /**
     * @return void
     */
    protected function buildFilter(): void
    {
        if ($this->filter === false) {
            $this->filter = new StubFilter();

        } else if (is_null($this->filter)) {
            $this->filter = new TextFilter([
                'name' => $this->getAttribute(),
            ]);

        } else if (is_array($this->filter)) {
            if (isset($this->filter['class']) && class_exists($this->filter['class'])) {
                $this->setFilter(
                    new $this->filter['class'](array_merge($this->filter, [
                            'name' => $this->getAttribute()
                        ])
                    )
                );
            }
        }
    }

    /**
     * @param Formattable $formatter
     */
    protected function setFormatter(Formattable $formatter): void
    {
        $this->format = $formatter;
    }

    /**
     * @throws Exception
     * @return void
     */
    protected function buildFormatter(): void
    {
        if (is_null($this->format)) {
            $class = self::FORMATTER_DEFINITIONS[self::FORMATTER_TEXT];
            $this->format = new $class;

        } else if (is_string($this->format)) {
            $class = self::FORMATTER_DEFINITIONS[$this->format] ?? self::FORMATTER_DEFINITIONS[self::FORMATTER_TEXT];
            $this->format = new $class;

        } else if (is_array($this->format)) {
            if (isset($this->format['class']) && class_exists($this->format['class'])) {
                $this->setFormatter(new $this->format['class']($this->format));
            }

        } else if (!is_object($this->format) || !($this->format instanceof Formattable)) {
            throw new Exception('Incorrect formatter.');
        }
    }
}
