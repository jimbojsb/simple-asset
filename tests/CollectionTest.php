<?php
use SimpleAsset\Collection,
    SimpleAsset\Manager;

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

        try {
            $collection->getAssets('foo');
            $this->fail('Should not be able to request a non-existant asset type');
        } catch (\Exception $e) {
        }
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
        $manager = new Manager;
        $manager->define('test', function() {
            $this->import('default');
            $this->style('/foo.css');
        });
        $manager->define('default', function() {
            $this->style('/default.css');
            $this->script('/default.js');
            $this->embeddedScript('var default = true;');
            $this->embeddedStyle('default { }');
        });
        $assets = $manager->getCollection('test')->getAssets();
        $this->assertEquals(2, count($assets['style']));
        $this->assertEquals(1, count($assets['script']));
        $this->assertEquals(1, count($assets['embeddedStyle']));
        $this->assertEquals(1, count($assets['embeddedScript']));

        $expectedAssets = [
            'style' => [
                '/default.css',
                '/foo.css'
            ],
            'script' => [
                '/default.js'
            ],
            'embeddedStyle' => [
                'default { }'
            ],
            'embeddedScript' => [
                'var default = true;'
            ]
        ];

        foreach ($expectedAssets as $type => $typeAssets) {
            for ($c = 0; $c < count($assets); $c++) {
                $actualAsset = $assets[$type][$c];
                switch (get_class($actualAsset)) {
                    case 'SimpleAsset\Style':
                    case 'SimpleAsset\Script':
                        $this->assertEquals($typeAssets[$c], $actualAsset->getSrc());
                        break;
                    case 'SimpleAsset\EmbeddedScript':
                        $this->assertEquals($typeAssets[$c], $actualAsset->getScript());
                        break;
                    case 'SimpleAsset\EmbeddedStyle':
                        $this->assertEquals($typeAssets[$c], $actualAsset->getStyle());
                        break;
                }
            }
        }
    }

    public function testGetName()
    {
        $c = new Collection('test');
        $this->assertEquals('test', $c->getName());
    }

    public function testAssetUniqueness()
    {
        $c = new Collection('test', function() {
            $this->style('/foo.css');
            $this->style('/foo.css');
            $this->style('/bar.css');
        });
        $assets = $c->getAssets('style');
        $this->assertEquals(2, count($assets));
    }
}