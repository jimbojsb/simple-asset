<?php
namespace SimpleAsset;

class Manager
{
    const TYPE_STYLE = 'style';
    const TYPE_SCRIPT = 'script';

    private $collections = array();
    private $selectedCollection;
    private $runtimeCollection;

    private static $publicRoot;

    public function __construct()
    {
        $this->runtimeCollection = new Collection('runtime');
    }

    public function __call($method, $args)
    {
        $runtimeCollectionProxyMethods = array(
            'style',
            'script',
            'embeddedStyle',
            'embeddedScript'
        );
        if (!in_Array($method, $runtimeCollectionProxyMethods)) {
            throw new \RuntimeException("Attempted to call a non-existent proxy method on runtime collection: $method");
        }
        call_user_func_array(array($this->runtimeCollection, $method), $args);
    }

    public static function getPublicRoot()
    {
        return self::$publicRoot;
    }

    public static function setPublicRoot($publicRoot)
    {
        self::$publicRoot = $publicRoot;
    }

    public function define($collectionName, \Closure $definition)
    {
        $this->collections[$collectionName] = new Collection($collectionName, $definition, $this);
    }

    public function select($collection)
    {
        $this->selectedCollection = $collection;
    }

    public function getCollection($collection)
    {
        if (!isset($this->collections[$collection])) {
            throw new \InvalidArgumentException("Cannot retrieve unknown collection: $collection");
        }
        return $this->collections[$collection];
    }

    public function renderStyleAssets()
    {
        $output = '';
        $collection = $this->collections[$this->selectedCollection];
        if ($collection) {
            $assets = $collection->getAssets();
            foreach ($assets['style'] as $asset) {
                $output .= $asset->render() . "\n";
            }
            foreach ($assets['embeddedStyle'] as $asset) {
                $output .= $asset->render() . "\n";
            }
        }
        $runtimeAssets = $this->runtimeCollection->getAssets();
        foreach ($runtimeAssets['style'] as $asset) {
            $output .= $asset->render() . "\n";
        }
        foreach ($runtimeAssets['embeddedStyle'] as $asset) {
            $output .= $asset->render() . "\n";
        }
        return $output;
    }

    public function renderScriptAssets()
    {
        $output = '';
        $collection = $this->collections[$this->selectedCollection];
        if ($collection) {
            $assets = $collection->getAssets();
            foreach ($assets['script'] as $asset) {
                $output .= $asset->render() . "\n";
            }
            foreach ($assets['embeddedScript'] as $asset) {
                $output .= $asset->render() . "\n";
            }
        }
        $runtimeAssets = $this->runtimeCollection->getAssets();
        foreach ($runtimeAssets['script'] as $asset) {
            $output .= $asset->render() . "\n";
        }
        foreach ($runtimeAssets['embeddedScript'] as $asset) {
            $output .= $asset->render() . "\n";
        }
        return $output;
    }
}