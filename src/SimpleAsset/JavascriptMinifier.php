<?php
namespace SimpleAsset;

class JavascriptMinifier
{
    public function minify($sourceFile, $destinationFile)
    {
        $cmd = sprintf("/usr/bin/env uglifyjs %s -c -o %s 2>/dev/null", $sourceFile, $destinationFile);
        $exitCode = null;
        system($cmd, $exitCode);
        if ($exitCode != 0) {
            throw new \RuntimeException("Error executing $cmd");
        }
    }
}