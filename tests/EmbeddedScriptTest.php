<?php
use SimpleAsset\EmbeddedScript;

class EmbeddedScriptTest extends PHPUnit_Framework_TestCase
{
    public function testRender()
    {
        $scriptString = 'var foo = 123;';
        $a = new EmbeddedScript($scriptString);
        $expectedString = '<script type="text/javascript">' . "\n" . $scriptString . "\n" . '</script>';
        $this->assertEquals($expectedString, $a->render());
    }
}