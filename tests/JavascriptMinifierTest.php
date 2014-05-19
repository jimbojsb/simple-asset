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

    public function testErrors()
    {
        $sourceFile = __DIR__ . '/resources/testerror.js';
        $destinationFile = __DIR__ . '/workdir/testerrormin.js';
        $m = new JavascriptMinifier;
        try {
            $m->minify($sourceFile, $destinationFile);
            $this->fail('Compilation of invalid javascript should have failed');
        } catch (Exception $e) {
        }
    }
}