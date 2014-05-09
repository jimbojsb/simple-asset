<?php
use SimpleAsset\Style,
    SimpleAsset\Manager;

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
        Manager::setPublicRoot(__DIR__ . '/resources');
        $a = new Style('/lesstest.less');
        $expectedString = '<link rel="stylesheet" type="text/css" href="/compiled-less/lesstest.css" media="all"/>';
        $this->assertEquals($expectedString, $a->render());

        $a = new Style('/less/lesstest.less');
        $expectedString = '<link rel="stylesheet" type="text/css" href="/compiled-less/lesstest.css" media="all"/>';
        $this->assertEquals($expectedString, $a->render());

        $a = new Style('/less/test/lesstest.less');
        $expectedString = '<link rel="stylesheet" type="text/css" href="/compiled-less/test/lesstest.css" media="all"/>';
        $this->assertEquals($expectedString, $a->render());
    }
}