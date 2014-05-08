<?php
use SimpleAsset\Collection,
    SimpleAsset\Style,
    SimpleAsset\Script;

class CollectionTest extends PHPUnit_Framework_TestCase
{
    public function testGetAssets()
    {
        $assets = function() {
            $this->style('/foo.css');
            $this->script('/foo.js');
            $this->embeddedStyle('foo { bar: 123; }');
            $this->embeddedScript('var foo = 123;');
        };
        $collection = new Collection('test', $assets);
        $assets = $collection->getAssets();
        $this->assertEquals('/foo.css', $assets['style'][0]->getSrc());
        $this->assertEquals('/foo.js', $assets['script'][0]->getSrc());
        $this->assertEquals('foo { bar: 123; }', $assets['embeddedStyle'][0]->getStyle());
        $this->assertEquals('var foo = 123;', $assets['embeddedScript'][0]->getScript());
    }

    public function testGetStyleAssets()
    {
        $assets = function() {
            $this->style('/foo.css');
        };
        $collection = new Collection('test', $assets);
        $styleAssets = $collection->getStyleAssets();
        $this->assertEquals(1, count($styleAssets));
        $this->assertEquals('/foo.css', $styleAssets[0]->getSrc());
    }

    public function testGetScriptAssets()
    {
        $assets = function() {
            $this->script('/foo.js');
        };
        $collection = new Collection('test', $assets);
        $scriptAssets = $collection->getScriptAssets();
        $this->assertEquals(1, count($scriptAssets));
        $this->assertEquals('/foo.js', $scriptAssets[0]->getSrc());
    }

    public function testFluentInterface()
    {
        $assets = function() {
            $this->style('/foo.css')
                 ->script('/foo.js')
                 ->embeddedStyle('foo { bar: 123; }')
                 ->embeddedScript('var foo = 123;');
        };
        $collection = new Collection('test', $assets);
        $generatedAssets = $collection->getAssets();
        $this->assertEquals(4, count($generatedAssets));
    }

    public function testImport()
    {

    }
}