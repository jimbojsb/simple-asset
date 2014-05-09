<?php
namespace SimpleAsset;

use SimpleAsset\LessCompiler;

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

    private function compileLess()
    {
        $inputFile = Manager::getPublicRoot() .  $this->src;
        $outputFile = Manager::getPublicRoot() . $this->generateLessFilename();
        LessCompiler::compile($inputFile, $outputFile);
    }

    private function generateLessFilename()
    {
        $srcParts = explode('/', $this->src);
        if (count($srcParts) == 2) {
            $sliceStart = 1;
        } else {
            $sliceStart = 2;
        }
        $srcParts = array_slice($srcParts, $sliceStart);
        array_unshift($srcParts, self::$compiledLessPrefix);
        $src = "/" . implode('/', $srcParts);
        $src = str_replace('.less', '.css', $src);
        return $src;
    }

    public function render()
    {
        $src = $this->src;
        if ($this->isLess) {
            $this->compileLess();
            $src = $this->generateLessFilename();
        }
        return sprintf('<link rel="stylesheet" type="text/css" href="%s" media="%s"/>', $src, $this->media);
    }
}