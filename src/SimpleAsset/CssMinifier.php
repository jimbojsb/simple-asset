<?php
namespace SimpleAsset\Minifier;

class CssMinifier
{
    private static $cssoPath = '/usr/local/bin/node /usr/local/bin/csso';

    public static function setCssoPath($path)
    {
        self::$cssoPath = $path;
    }

    public static function minify($sourceFile, $destinationFile)
    {
        if (!file_exists(self::$cssoPath)) {
            throw new \Exception('Unable to invoke ' . self::$cssoPath);
        }

        if (!is_writable(dirname($destinationFile))) {
            throw new \Exception('Unable to create/write to ' . dirname($destinationFile));
        }

        $cmd = sprintf("%s --input %s --ouptut %s", self::$cssoPath, $sourceFile, $destinationFile);
        system($cmd);
    }
}