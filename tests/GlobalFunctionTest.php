<?php
use SimpleAsset\Manager,
    SimpleAsset\Collection;

class GlobalFunctionTest extends PHPUnit_Framework_TestCase
{
    public function testRegisterGlobalFunction()
    {
        $this->assertFalse(function_exists('AssetManager'));
        Manager::registerGlobalFunction();
        $this->assertTrue(function_exists('AssetManager'));
    }

    public function testGlobalFunctionIsASingelton()
    {
        Manager::registerGlobalFunction();

        $obj1 = AssetManager();
        $obj2 = AssetManager();
        $this->assertEquals(spl_object_hash($obj1), spl_object_hash($obj2));
    }

    public function testGetCollectionProxy()
    {
        Manager::registerGlobalFunction();

        AssetManager('test', function() {

        });
        $test = AssetManager('test');
        $this->assertTrue($test instanceof Collection);
    }

    public function testRuntimeCollectionProxy()
    {
        Manager::registerGlobalFunction();
        AssetManager()->style('/foo.css');
        $assets = AssetManager()->getRuntimeCollection()->getAssets('style');
        $this->assertEquals(1, sizeof($assets));
    }
}