<?php
namespace SimpleAsset;

class Manager
{
    const TYPE_STYLE = 'style';
    const TYPE_SCRIPT = 'script';

    private $collections = array();
    private $selectedCollection;
    private $publicRoot;

    public function getPublicRoot()
    {
        return $this->publicRoot;
    }

    public function setPublicRoot($publicRoot)
    {
        $this->publicRoot = $publicRoot;
    }

    public function define($collectionName, \Closure $definition)
    {
        $this->collections[$collectionName] = new Collection($collectionName, $definition);
    }

    public function select($stackName)
    {
        $this->selectedStack = $stackName;
        unset($this->assets);
    }

    public function renderStyles()
    {
        $this->cacheAssets();
        if ($this->selectedStack) {
            $output = '';
            $styles = $this->getAssetsForStack($this->selectedStack, self::TYPE_STYLE);
            foreach ($styles as $style) {
                $output .= $style->render() . "\n";
            }
            return $output;
        }
    }

    public function renderScripts()
    {
        $this->cacheAssets();
        if ($this->selectedStack)
        {
            $output = '';
            $scripts = $this->getAssetsForStack($this->selectedStack, self::TYPE_SCRIPT);
            foreach ($scripts as $script) {
                $output .= $script->render() . "\n";
            }
            return $output;
        }
    }

    private function cacheAssets()
    {
        if (!$this->stackAssetsCache) {
            $this->stackAssetsCache = $this->getAssetsForStack($this->selectedStack);
        }
    }

    public function getAssets()
    {
        $assets = [];
        foreach ($this->stacks as $stack => $files) {
            $assets[$stack] = $this->getAssetsForStack($stack);
        }
        return $assets;
    }

    public function getAssetsForStack($stack, $type = null)
    {
        if ($this->stackAssetsCache) {
            if ($type) {
                return $this->stackAssetsCache[$type];
            }
            return $this->stackAssetsCache;
        }

        $stackFiles = $this->stacks[$stack];
        $assets = [];

        $dedupe = [];
        foreach ($stackFiles as $stackFile) {
            $fileType = key($stackFile);
            $value = current($stackFile);
            if (!is_array($value) && isset($dedupe[$value])) {
                continue;
            } else if (!is_array($value)) {
                $dedupe[$value] = true;
            }
            if ($fileType == 'import') {
                if (!is_array($value)) {
                    $value = array($value);
                }
                foreach ($value as $imported) {
                    $assets =  array_merge_recursive($assets, $this->getAssetsForStack($imported));
                }
            } else {
                $className = 'SimpleAsset\Asset\\' . ucfirst($fileType) . 'Asset';
                $asset = new $className($stackFile);
                $assets[$asset->getType()][] = $asset;
            }
        }

        if ($type) {
            return $assets[$type];
        } else {
            return $assets;
        }
    }
}