<?php
use SimpleAsset\Manager;

class ManagerTest extends PHPUnit_Framework_TestCase
{
    public function testPublicRoot()
    {
        $manager = new Manager;
        $manager->setPublicRoot('foo');
        $this->assertEquals('foo', $manager->getPublicRoot());
    }

    public function testDefine()
    {
        $m = new Manager;
        $m->define('test', function() {});
        $this->assertInstanceOf('SimpleAsset\Collection', $m->getCollection('test'));
    }

    public function testGetCollection()
    {
        $m = new Manager;
        $m->define('test', function() {});
        $this->assertInstanceOf('SimpleAsset\Collection', $m->getCollection('test'));

        try {
            $m->getCollection('foo');
            $this->fail('Should have throw an invalid argument exception trying to access a non-existing collection');
        } catch (InvalidArgumentException $e) {
        }
    }

    public function testRuntimeCollectionWontProxyInvalidMethods()
    {
        $m = new Manager;
        try {
            $m->foo();
            $this->fail('Manager should not proxy methods not explicity allowed on runtime collection');
        } catch (RuntimeException $e) {
        }
    }

    public function testRenderStyleAssets()
    {

    }

    public function testRenderScriptAssets()
    {

    }
}