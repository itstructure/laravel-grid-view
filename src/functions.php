<?php
/**
 * @param array $config
 * @return string
 */
function grid_view(array $config)
{
    return (new \Itstructure\GridView\Grid($config))->render();
}
