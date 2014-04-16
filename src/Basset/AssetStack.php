<?php
namespace Basset;

class AssetStack
{
    private $stacks;
    private $selectedStack;
    private $cssBasePath;
    private $jsBasePath;
    private $useCdnUrl = false;
    private $dynamicScripts = array();

    public function __construct($stacks)
    {
        $this->stacks = $stacks;
    }

    public function selectStack($stack)
    {
        $this->selectedStack = $stack;
    }

    public function setCssBasePath($path)
    {
        $this->cssBasePath = $path;
    }

    public function setJsBasePath($path)
    {
        $this->jsBasePath = $path;
    }

    public function enableCdnUrl($bool)
    {
        $this->useCdnUrl = $bool;
    }

    public function renderCssTags()
    {

    }

    public function renderJavascriptTags()
    {

    }
}