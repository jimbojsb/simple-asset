<?php
namespace Basset\Asset;

class CssAsset
{
    private $src;
    private $basePath;
    private $media;

    public function __construct($src, $media = 'all')
    {
        $this->media = $media;
        $this->src = $src;
    }

    public function __toString()
    {
        return sprintf('<link rel="stylesheet" type="text/css" href="%s" media="%s"/>', $this->src, $this->media);
    }
}