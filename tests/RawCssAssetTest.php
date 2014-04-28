<?php
use SimpleAsset\Asset\RawCssAsset;

class RawCssAssetTest extends PHPUnit_Framework_TestCase
{
    public function testRenderTag()
    {
        $styleString = 'body { font-weight: normal; }';
        $a = new RawCssAsset($styleString);
        $expectedString = '<style type="text/css">' . "\n$styleString\n" . '</style>';
        $this->assertEquals($expectedString, $a->__toString());
    }
}