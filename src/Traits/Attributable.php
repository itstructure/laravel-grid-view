<?php
namespace Itstructure\GridView\Traits;

use Itstructure\GridView\Helpers\HtmlHelper;

/**
 * Class Attributable
 * @package Itstructure\GridView\Traits
 */
trait Attributable
{
    /**
     * @var array
     */
    protected static $dataAttributes = ['data', 'data-ng', 'ng'];

    /**
     * @var array
     */
    protected $htmlAttributes = [];

    /**
     * @var string
     */
    protected $charset = 'UTF-8';

    /**
     * @return string
     */
    public function buildHtmlAttributes()
    {
        $html = '';

        foreach ($this->htmlAttributes as $name => $value) {
            if (is_bool($value)) {
                if ($value) {
                    $html .= " $name";
                }
                continue;
            }

            if (is_array($value)) {
                if (in_array($name, static::$dataAttributes)) {
                    foreach ($value as $n => $v) {
                        if (is_string($v)) {
                            $html .= " $name-$n=\"" . HtmlHelper::encode($v, $this->charset) . '"';
                        }
                    }
                    continue;
                }

                if ($name === 'class') {
                    if (empty($value)) {
                        continue;
                    }
                    $html .= " $name=\"" . HtmlHelper::encode(implode(' ', $value), $this->charset) . '"';
                    continue;
                }

                if ($name === 'style') {
                    if (empty($value)) {
                        continue;
                    }
                    $html .= " $name=\"" . HtmlHelper::encode(HtmlHelper::cssStyleFromArray($value), $this->charset) . '"';
                }
                continue;
            }

            if ($value !== null) {
                $html .= " $name=\"" . HtmlHelper::encode($value, $this->charset) . '"';
            }
        }

        return $html;
    }
}
