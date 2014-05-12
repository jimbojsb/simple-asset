<?php
use SimpleAsset\CssMinifier;

class CssMiniferTest extends PHPUnit_Framework_TestCase
{
    public function testMinify()
    {
        $sourceFile = __DIR__ . '/resources/test.css';
        $destinationFile = __DIR__ . '/workdir/testmin.css';

        $sourceSize = filesize($sourceFile);

        CssMinifier::minify($sourceFile, $destinationFile);
        $this->assertTrue(file_exists($destinationFile));

        $destinationSize = filesize($destinationFile);

        $this->assertTrue($destinationSize < $sourceSize);
        @unlink($destinationFile);
    }
}