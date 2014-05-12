<?php
namespace SimpleAsset;

class Script implements AssetInterface, ScriptInterface, ExternalAssetInterface
{
    private $src;

    public function __construct($src)
    {
        $this->src = $src;
    }

    public function getSrc()
    {
        return $this->src;
    }

    public function isEmbedded()
    {
        return false;
    }

    public function render()
    {
        return sprintf('<script type="text/javascript" src="%s"></script>', $this->src);
    }
}