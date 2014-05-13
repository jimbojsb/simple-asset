<?php
namespace SimpleAsset;

class CssMinifier
{
    public function minify($sourceFile, $destinationFile)
    {
        if (!is_writable(dirname($destinationFile))) {
            throw new \Exception('Unable to create/write to ' . dirname($destinationFile));
        }

        $cmd = sprintf("/usr/bin/env csso --input %s --ouptut %s", $sourceFile, $destinationFile);
        system($cmd);
    }
}