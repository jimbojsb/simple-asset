<?php
namespace SimpleAsset;

class Script
{
    private $src;

    public function __construct($src)
    {
        $this->src = $src;
    }

    public function render()
    {
        return sprintf('<script type="text/javascript" src="%s"></script>', $this->src);
    }
}