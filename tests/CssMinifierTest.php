<?php
use SimpleAsset\CssMinifier;

class CssMiniferTest extends PHPUnit_Framework_TestCase
{
    public function testMinify()
    {
        $sourceFile = __DIR__ . '/resources/test.css';
        $destinationFile = __DIR__ . '/workdir/testmin.css';

        $sourceSize = filesize($sourceFile);

        $minifier = new CssMinifier;
        $minifier->setCssoPath(CSSO_PATH);

        $minifier->minify($sourceFile, $destinationFile);
        $this->assertTrue(file_exists($destinationFile));

        $destinationSize = filesize($destinationFile);

        $this->assertTrue($destinationSize < $sourceSize);
        @unlink($destinationFile);
    }

    public function testExceptionForInvalidCsso()
    {
        $minifier = new CssMinifier;
        $minifier->setCssoPath('foo');
        $this->setExpectedException('Exception');
        $minifier->minify('foo', 'bar');
    }
}