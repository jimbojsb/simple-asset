<?php
use SimpleAsset\Manager;

function AssetCollection($collection, Closure $definition = null)
{
    static $instance;
    if (!($instance instanceof Manager)) {
        $instance = new Manager();
    }

    if ($collection) {
        if ($definition) {
            $instance->define($collection, $definition);
        } else {
            return $instance->get($collection);
        }
    }
    return $instance;
}