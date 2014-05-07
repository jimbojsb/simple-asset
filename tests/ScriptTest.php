<?php
use SimpleAsset\Script;

class ScriptTest extends PHPUnit_Framework_TestCase
{
    public function testRenderTag()
    {
        $a = new Script('/foo.js');
        $expectedString = '<script type="text/javascript" src="/foo.js"></script>';
        $this->assertEquals($expectedString, $a->render());
    }
}