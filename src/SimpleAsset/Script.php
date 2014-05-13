<?php
namespace SimpleAsset;

class Script implements AssetInterface, ScriptInterface, ExternalAssetInterface
{
    private $src;
    private $isRemote = false;
    private $baseUrl;

    public function __construct($src)
    {
        $this->src = $src;
        if (
            substr($src, 0, 2) == '//' ||
            substr($src, 0, 4) == 'http'
        ) {
            $this->isRemote = true;
        }
    }

    public function isRemote()
    {
        return $this->isRemote;
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
        return sprintf('<script type="text/javascript" src="%s%s"></script>', $this->baseUrl, $this->src);
    }
}