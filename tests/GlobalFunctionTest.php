<?php
use SimpleAsset\Manager,
    SimpleAsset\Collection;

class GlobalFunctionTest extends PHPUnit_Framework_TestCase
{
    public function testRegisterGlobalFunction()
    {
        $m = new Manager;
        $this->assertFalse(function_exists('AssetManager'));
        $m->registerGlobalFunction();
        $this->assertTrue(function_exists('AssetManager'));
    }

    public function testGlobalFunctionIsASingelton()
    {
        $m = new Manager;
        $m->registerGlobalFunction();

        $obj1 = AssetManager();
        $obj2 = AssetManager();
        $this->assertEquals(spl_object_hash($obj1), spl_object_hash($obj2));
    }

    public function testProxyCalls()
    {
        $m = new Manager;
        $m->registerGlobalFunction();

        AssetManager('test', function() {

        });
        $test = AssetManager('test');
        $this->assertTrue($test instanceof Collection);
    }
}