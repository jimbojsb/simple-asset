<?php
use SimpleAsset\EmbeddedStyle;

class EmbeddedStyleTest extends PHPUnit_Framework_TestCase
{
    public function testRender()
    {
        $styleString = 'body { background-color: red; }';
        $a = new EmbeddedStyle($styleString);
        $expectedString = '<style type="text/css">' . "\n" . $styleString . "\n" . '</style>';
        $this->assertEquals($expectedString, $a->render());
    }
}