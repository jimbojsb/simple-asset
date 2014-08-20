<?php
namespace SimpleAsset;

class CssMinifier
{
    public function minify($sourceFile, $destinationFile)
    {
        $cmd = sprintf("/usr/bin/env cssmin %s > %s", $sourceFile, $destinationFile);
        system($cmd);
    }
}