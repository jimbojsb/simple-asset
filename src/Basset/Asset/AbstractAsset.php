<?php
namespace Basset\Asset;

abstract class AbstractAsset
{
    private $tagAttributes = array();
    private

    public function addAttribute($attr, $value)
    {
        $this->tagAttributes[$attr] = $value;
    }

    abstract function renderTag();
}