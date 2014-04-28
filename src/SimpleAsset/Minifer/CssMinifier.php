<?php
namespace SimpleAsset\Minifier;

class CssMinifier
{
    private static $cssoPath = '/usr/local/bin/csso';
    private static $tmpDir = '/tmp/cssmin';

    public static function setCssoPath($path)
    {
        self::$cssoPath = $path;
    }

    public static function setTempDir($path)
    {
        self::$tmpDir = $path;
    }

    public static function minify($source)
    {
        if (!file_exists(self::$cssoPath)) {
            throw new \Exception('Unable to invoke ' . self::$cssoPath);
        }

        @mkdir(self::$tmpDir, true);
        if (!file_exists(self::$tmpDir)) {
            throw new \Exception('Unable to create/write to ' . self::$tmpDir);
        }

        $tmpfileName = self::$tmpDir . '/' . md5(time() + mt_rand(1,10));
        file_put_contents($tmpfileName, $source);
        $output = system(self::$cssoPath . ' ' . $tmpfileName);
        unlink($tmpfileName);
        return $output;
    }
}