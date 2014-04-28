<?php
namespace SimpleAsset\Asset;

use SimpleAsset\Compiler\LessCompiler;

class LessAsset implements AssetInterface
{
    private static $sourceRoot;
    private static $destinationRoot;
    private static $publicBaseUrl;

    private $src;
    private $media = 'all';
    private $embed = false;


    public function __construct(array $data)
    {
        $this->src = $data['less'];
        if (isset($data['media'])) {
            $this->media = $data['media'];
        }
        if (isset($data['embed'])) {
            $this->embed = $data['embed'];
        }
    }

    public function getType()
    {
        return self::TYPE_STYLE;
    }

    public function getSource()
    {
        return file_get_contents(self::getDestinationFilename());
    }

    private function getSourceFilename()
    {
        return self::$sourceRoot . '/' . $this->src;
    }

    private function getDestinationFilename()
    {
        return self::$destinationRoot . '/' . str_replace('.less', '.css', $this->src);
    }

    public static function setSourceRoot($sourceRoot)
    {
        self::$sourceRoot = $sourceRoot;
    }

    public static function setDestinationRoot($destRoot)
    {
        self::$destinationRoot = $destRoot;
    }

    public static function setPublicBaseUrl($baseUrl)
    {
        self::$publicBaseUrl = $baseUrl;
    }

    public function render()
    {
        @mkdir(self::$destinationRoot, 0777, true);
        LessCompiler::compile($this->getSourceFilename(), $this->getDestinationFilename());
        if ($this->embed) {
            $output = '<style type="text/css">' . $this->getSource() . '</style>';
            return $output;
        } else {
            $src = self::$publicBaseUrl . '/' . str_replace('.less', '.css', $this->src);
            return sprintf('<link rel="stylesheet" type="text/css" href="%s" media="%s"/>', $src, $this->media);
        }
    }
}