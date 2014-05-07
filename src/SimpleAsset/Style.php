<?php
namespace SimpleAsset;

class Style
{
    protected $media = 'all';
    protected $src;

    protected static $compiledLessPrefix = 'compiled-less';

    public function __construct($src, $media = null)
    {
        $this->src = $src;
        if ($media) {
            $this->media = $media;
        }
    }

    public function render()
    {
        return sprintf('<link rel="stylesheet" type="text/css" href="%s" media="%s"/>', $this->src, $this->media);
    }
}