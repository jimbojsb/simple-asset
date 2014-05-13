<?php
namespace SimpleAsset;

class JavascriptMinifier
{
    private $uglifyJsPath = '/usr/local/bin/node /usr/local/bin/uglifyjs';

    public function setUglifyJsPath($path)
    {
        $this->uglifyJsPath = $path;
    }

    public function minify($sourceFile, $destinationFile)
    {
        $cmd = sprintf("%s %s -c -o %s", $this->uglifyJsPath, $sourceFile, $destinationFile);
        $exitCode = null;
        system($cmd, $exitCode);
        if ($exitCode != 0) {
            throw new \RuntimeException("Error executing $cmd");
        }
    }
}