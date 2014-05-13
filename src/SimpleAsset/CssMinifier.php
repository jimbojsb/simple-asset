<?php
namespace SimpleAsset;

class CssMinifier
{
    public function minify($sourceFile, $destinationFile)
    {
        $cmd = sprintf("/usr/bin/env csso --input %s --ouptut %s", $sourceFile, $destinationFile);
        system($cmd);
    }
}