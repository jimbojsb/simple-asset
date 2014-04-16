<?php
use Basset\Asset\CssAsset;

class CssAssetTest extends PHPUnit_Framework_TestCase
{
    public function testRenderTag()
    {
        $a = new CssAsset('/foo.css');
        $expectedString = '<link rel="stylesheet" type="text/css" href="/foo.css" media="all"/>';
        $this->assertEquals($expectedString, $a->__toString());
    }
}