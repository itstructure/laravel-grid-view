<?php
namespace Itstructure\GridView\Helpers;

/**
 * Class HtmlHelper
 * @package Itstructure\GridView\Helpers
 */
class HtmlHelper
{
    /**
     * @param $content
     * @param string $charset
     * @param bool $doubleEncode
     * @return string
     */
    public static function encode($content, $charset = 'UTF-8', $doubleEncode = true)
    {
        return htmlspecialchars($content, ENT_QUOTES | ENT_SUBSTITUTE, $charset, $doubleEncode);
    }

    /**
     * @param array $style
     * @return null|string
     */
    public static function cssStyleFromArray(array $style)
    {
        $output = '';
        foreach ($style as $name => $value) {
            $output .= "$name: $value; ";
        }

        return $output === '' ? null : rtrim($output);
    }
}
