<?php
namespace SimpleAsset;

class Style
{
    protected $media = 'all';
    protected $src;
    protected $isLess = false;

    protected static $compiledLessPrefix = 'compiled-less';

    public function __construct($src, $media = null)
    {
        $this->src = $src;
        if ($media) {
            $this->media = $media;
        }
        if (substr($src, -4) == 'less') {
            $this->isLess = true;
        }
    }

    public function getSrc()
    {
        return $this->src;
    }

    public function render()
    {
        $src = $this->src;
        if ($this->isLess) {
            $srcParts = explode('/', $src);
            if (count($srcParts) == 2) {
                $sliceStart = 1;
            } else {
                $sliceStart = 2;
            }
            $srcParts = array_slice($srcParts, $sliceStart);
            array_unshift($srcParts, self::$compiledLessPrefix);
            $src = "/" . implode('/', $srcParts);
            $src = str_replace('.less', '.css', $src);
        }
        return sprintf('<link rel="stylesheet" type="text/css" href="%s" media="%s"/>', $src, $this->media);
    }
}