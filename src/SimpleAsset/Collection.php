<?php
namespace SimpleAsset;

class Collection
{
    private $name;
    private $definition;
    private $manager;
    private $styleAssets = array();
    private $scriptAssets = array();
    private $embdeddedScriptAssets = array();
    private $embeddedStyleAssets = array();
    private $assetsLoaded = false;

    public function __construct($name, \Closure $definition = null, Manager $manager = null)
    {
        $this->name = $name;
        $this->definition = $definition;
        $this->manager = $manager;
    }

    public function import()
    {
        $imports = func_get_args();
    }

    public function script($src)
    {
        $this->scriptAssets[] = new Script($src);
        return $this;
    }

    public function style($src)
    {
        $this->styleAssets[] = new Style($src);
        return $this;
    }

    public function embeddedScript($script)
    {
        $this->embdeddedScriptAssets[] = new EmbeddedScript($script);
        return $this;
    }

    public function embeddedStyle($style)
    {
        $this->embeddedStyleAssets[] = new EmbeddedStyle($style);
        return $this;
    }

    public function getAssets()
    {
        $this->loadAssets();
        return array(
            "style" => $this->styleAssets,
            "script" => $this->scriptAssets,
            "embeddedStyle" => $this->embeddedStyleAssets,
            "embeddedScript" => $this->embdeddedScriptAssets
        );
    }

    public function getStyleAssets()
    {
        $this->loadAssets();
        return $this->styleAssets;
    }

    public function getScriptAssets()
    {
        $this->loadAssets();
        return $this->scriptAssets;
    }

    private function loadAssets()
    {
        if (!$this->assetsLoaded && $this->definition) {
            $definition = $this->definition->bindTo($this);
            $definition();
            $this->assetsLoaded = true;
        }
    }
}