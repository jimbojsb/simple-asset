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

    public function testIsEmbedded()
    {
        $this->assertFalse((new Script('/foo.js'))->isEmbedded());
    }

    public function testIsRemote()
    {
        $s = new Script('/foo.js');
        $this->assertFalse($s->isRemote());

        $s = new Script('//foo.com/bar.js');
        $this->assertTrue($s->isRemote());

        $s = new Script('http://foo.com/bar.js');
        $this->assertTrue($s->isRemote());

        $s = new Script('https://foo.com/bar.js');
        $this->assertTrue($s->isRemote());

        $s = new Script('../foo/bar.js');
        $this->assertFalse($s->isRemote());
    }
}