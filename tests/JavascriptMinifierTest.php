<?php
use SimpleAsset\JavascriptMinifier;

class JavascriptMinifierTest extends PHPUnit_Framework_TestCase
{
    public function testMinify()
    {
        $sourceFile = __DIR__ . '/resources/test.js';
        $destinationFile = __DIR__ . '/workdir/testmin.js';

        $sourceSize = filesize($sourceFile);

        $minifier = new JavascriptMinifier;

        $minifier->minify($sourceFile, $destinationFile);
        $this->assertTrue(file_exists($destinationFile));

        $destinationSize = filesize($destinationFile);

        $this->assertTrue($destinationSize < $sourceSize);
        @unlink($destinationFile);
    }
}