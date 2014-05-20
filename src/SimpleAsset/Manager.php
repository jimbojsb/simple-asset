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

    public function registerGlobalFunction()
    {
        require_once __DIR__ . '/../assetmanager.php';
    }

    public function useCdn($cdnBaseUrl)
    {
        $this->cdnBaseUrl = $cdnBaseUrl;
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

    public function getCollections()
    {
        return array_values($this->collections);
    }

    public function renderStyleAssets()
    {
        $output = '';
        $collection = $this->collections[$this->selectedCollection];
        if ($collection) {
            if ($this->cdnBaseUrl) {
                $assetPath = "$this->cdnBaseUrl/" . $collection->getName();
                if ($this->clientAcceptsGzip()) {
                    $assetPath .= '.gz';
                }
                $output .= sprintf('<link rel="stylesheet" type="text/css" href="%s.css" media="all"/>%s', $assetPath, "\n");;
            } else {
                $assets = $collection->getAssets();
                foreach ($assets['style'] as $asset) {
                    $output .= $asset->render() . "\n";
                }
                foreach ($assets['embeddedStyle'] as $asset) {
                    $output .= $asset->render() . "\n";
                }
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