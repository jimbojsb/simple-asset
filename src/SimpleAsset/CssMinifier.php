<?php
namespace SimpleAsset;

class CssMinifier
{
    private $cssoPath = '/usr/local/bin/csso';

    public function setCssoPath($path)
    {
        $this->cssoPath = $path;
    }

    public function minify($sourceFile, $destinationFile)
    {
        if (!file_exists($this->cssoPath)) {
            throw new \Exception('Unable to invoke ' . $this->cssoPath);
        }

        if (!is_writable(dirname($destinationFile))) {
            throw new \Exception('Unable to create/write to ' . dirname($destinationFile));
        }

        $cmd = sprintf("%s --input %s --ouptut %s", $this->cssoPath, $sourceFile, $destinationFile);
        system($cmd);
    }
}