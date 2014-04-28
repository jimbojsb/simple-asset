<?php
namespace SimpleAsset;

use SimpleAsset\Asset\LessAsset,
    SimpleAsset\Asset\CssAsset,
    SimpleAsset\Asset\JavascriptAsset,
    SimpleAsset\Asset\AssetInterface as Asset;


class Manager
{
    private $stacks = array();
    private $selectedStack;
    private $assets = array();

    public function __construct(array $stacks = array())
    {
        $this->stacks = $stacks;
    }

    public function select($stackName)
    {
        $this->selectedStack = $stackName;
    }

    public function renderStyles()
    {
        $output = '';
        $styles = $this->getAssets($this->selectedStack, Asset::TYPE_STYLE);
        foreach ($styles as $style) {
            $output .= $style->render() . "\n";
        }
        return $output;
    }

    public function renderScripts()
    {
        $output = '';
        $scripts = $this->getAssets($this->selectedStack, Asset::TYPE_SCRIPT);
        foreach ($scripts as $script) {
            $output .= $script->render() . "\n";
        }
        return $output;
    }

    public function getAssets($stack, $type = null)
    {
        if (!$this->assets) {
            $stackFiles = $this->stacks[$stack];
            $assets = [];

            foreach ($stackFiles as $stackFile) {
                $fileType = key($stackFile);
                $value = current($stackFile);
                if ($fileType == 'import') {
                    if (!is_array($value)) {
                        $value = array($value);
                    }
                    foreach ($value as $imported) {
                        $assets =  array_merge_recursive($assets, $this->getAssets($imported));
                    }
                } else {
                    $className = 'SimpleAsset\Asset\\' . ucfirst($fileType) . 'Asset';
                    $asset = new $className($stackFile);
                    $assets[$asset->getType()][] = $asset;
                }
            }
            $this->assets = $assets;
        }

        if ($type) {
            return $this->assets[$type];
        } else {
            return $this->assets;
        }
    }
}