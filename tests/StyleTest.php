<?php
use SimpleAsset\Style;

class StyleTest extends PHPUnit_Framework_TestCase
{
    public function testRenderTag()
    {
        $a = new Style('/foo.css');
        $expectedString = '<link rel="stylesheet" type="text/css" href="/foo.css" media="all"/>';
        $this->assertEquals($expectedString, $a->render());
    }
}