<?php
use SimpleAsset\Manager;

class ManagerTest extends PHPUnit_Framework_TestCase
{
    public function testPublicRoot()
    {
        Manager::setPublicRoot('foo');
        $this->assertEquals('foo', Manager::getPublicRoot());
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
        $manager = new Manager;
        $manager->define('test', function() {
            $this->style('/foo.css');
            $this->embeddedStyle('foo');
        });
        $manager->style('/bar.css', 'print');
        $manager->embeddedStyle('bar');
        $manager->select('test');

        $output = $manager->renderStyleAssets();
        $expectedOutput = <<<EOT
<link rel="stylesheet" type="text/css" href="/foo.css" media="all"/>
<style type="text/css">
foo
</style>
<link rel="stylesheet" type="text/css" href="/bar.css" media="print"/>
<style type="text/css">
bar
</style>

EOT;
        $this->assertEquals($expectedOutput, $output);
    }

    public function testRenderScriptAssets()
    {
        $manager = new Manager;
        $manager->define('test', function() {
            $this->script('/foo.js');
            $this->embeddedScript('foo');
        });
        $manager->script('/bar.js');
        $manager->embeddedScript('bar');
        $manager->select('test');

        $output = $manager->renderScriptAssets();
        $expectedOutput = <<<EOT
<script type="text/javascript" src="/foo.js"></script>
<script type="text/javascript">
foo
</script>
<script type="text/javascript" src="/bar.js"></script>
<script type="text/javascript">
bar
</script>

EOT;
        $this->assertEquals($expectedOutput, $output);
    }


}