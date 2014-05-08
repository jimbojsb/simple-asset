<?php
use SimpleAsset\Style;

class StyleTest extends PHPUnit_Framework_TestCase
{
    public function testRenderTag()
    {
        $a = new Style('/foo.css');
        $expectedString = '<link rel="stylesheet" type="text/css" href="/foo.css" media="all"/>';
        $this->assertEquals($expectedString, $a->render());

        $a = new Style('/foo.css', 'print');
        $expectedString = '<link rel="stylesheet" type="text/css" href="/foo.css" media="print"/>';
        $this->assertEquals($expectedString, $a->render());
    }

    public function testRenderTagForLessAsset()
    {
        $a = new Style('/foo.less');
        $expectedString = '<link rel="stylesheet" type="text/css" href="/compiled-less/foo.css" media="all"/>';
        $this->assertEquals($expectedString, $a->render());

        $a = new Style('/css/foo.less');
        $expectedString = '<link rel="stylesheet" type="text/css" href="/compiled-less/foo.css" media="all"/>';
        $this->assertEquals($expectedString, $a->render());

        $a = new Style('/css/bar/foo.less');
        $expectedString = '<link rel="stylesheet" type="text/css" href="/compiled-less/bar/foo.css" media="all"/>';
        $this->assertEquals($expectedString, $a->render());
    }
}