<?php
namespace SimpleAsset;

class Style implements AssetInterface, StyleInterface, ExternalAssetInterface
{
    protected $media = 'all';
    protected $src;
    protected $isLess = false;
    protected $publicRoot;
    protected $isRemote = false;
    protected $baseUrl;

    public function __construct($src, $media = 'all')
    {
        $this->src = $src;
        if ($media) {
            $this->media = $media;
        }
        if (substr($src, -4) == 'less') {
            $this->isLess = true;
        }
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

    public function isLess()
    {
        return $this->isLess;
    }

    public function setPublicRoot($root)
    {
        $this->publicRoot = $root;
    }

    public function getSrc()
    {
        return $this->src;
    }

    private function compileLess()
    {
        $inputFile = $this->publicRoot .  $this->src;
        $outputFile = $this->publicRoot . $this->generateLessFilename();
        $compiler = new LessCompiler;
        $compiler->compile($inputFile, $outputFile);
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
        array_unshift($srcParts, 'compiled-less');
        $src = "/" . implode('/', $srcParts);
        $src = str_replace('.less', '.css', $src);
        return $src;
    }

    public function isEmbedded()
    {
        return false;
    }

    public function render()
    {
        $src = $this->src;
        if ($this->isLess) {
            $this->compileLess();
            $src = $this->generateLessFilename();
        }
        return sprintf('<link rel="stylesheet" type="text/css" href="%s%s" media="%s"/>', $this->baseUrl, $src, $this->media);
    }
}