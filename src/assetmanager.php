<?php
use SimpleAsset\Manager;

function AssetManager($collection = null, Closure $definition = null)
{
    static $instance;
    if (!($instance instanceof Manager)) {
        $instance = new Manager();
    }

    if ($collection) {
        if ($definition) {
            $instance->define($collection, $definition);
        } else {
            return $instance->getCollection($collection);
        }
    }
    return $instance;
}