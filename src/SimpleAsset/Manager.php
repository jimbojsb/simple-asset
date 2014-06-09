<?php
namespace SimpleAsset;

class Manager
{
    const TYPE_STYLE = 'style';
    const TYPE_SCRIPT = 'script';

    private $collections = array();
    private $selectedCollection;
    private $runtimeCollection;
    private $publicRoot;
    private $cdnBaseUrl;

    public function __construct()
    {
        $this->runtimeCollection = new Collection('runtime');
    }

    public static function registerGlobalFunction()
    {
        if (!function_exists('AssetManager')) {
            require_once __DIR__ . '/../assetmanager.php';
        }
    }

    public function useCdn($cdnBaseUrl)
    {
        $this->cdnBaseUrl = $cdnBaseUrl;
    }

    public function __call($method, $args)
    {
        $ro = new \ReflectionObject($this->runtimeCollection);
        if ($ro->hasMethod($method)) {
            call_user_func_array([$this->runtimeCollection, $method], $args);
        } else {
            throw new \RuntimeException("Attempted to call a non-existent proxy method on runtime collection: $method");
        }

    }

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

    public function getRuntimeCollection()
    {
        return $this->runtimeCollection;
    }

    public function getCollections()
    {
        return array_values($this->collections);
    }

    public function renderStyleAssets()
    {
        $output = '';
        $collection = $this->collections[$this->selectedCollection];
        if ($collection) {
            $assets = $collection->getAssets();
            foreach ($assets['style'] as $asset) {
                if ($asset->isRemote()) {
                    $output .= $asset->render() . "\n";
                } else if (!$this->cdnBaseUrl) {
                    $output .= $asset->render() . "\n";
                }
            }
            if ($this->cdnBaseUrl) {
                $assetPath = "$this->cdnBaseUrl/" . $collection->getName();
                if ($this->clientAcceptsGzip()) {
                    $assetPath .= '.gz';
                }
                $assetPath .= '.css';
                $style = new Style($assetPath);
                $output .= $style->render() . "\n";
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

    private function clientAcceptsGzip()
    {
        return (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false);
    }

    public function renderScriptAssets()
    {
        $output = '';
        $collection = $this->collections[$this->selectedCollection];
        if ($collection) {
            $assets = $collection->getAssets();
            foreach ($assets['script'] as $asset) {
                if ($asset->isRemote()) {
                    $output .= $asset->render() . "\n";
                } else if (!$this->cdnBaseUrl) {
                    $output .= $asset->render() . "\n";
                }
            }
            if ($this->cdnBaseUrl) {
                $assetPath = "$this->cdnBaseUrl/" . $collection->getName();
                if ($this->clientAcceptsGzip()) {
                    $assetPath .= '.gz';
                }
                $assetPath .= '.js';
                $style = new Script($assetPath);
                $output .= $style->render() . "\n";
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