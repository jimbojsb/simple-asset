<?php
namespace SimpleAsset\Asset;

class CssAsset implements AssetInterface
{
    protected $style;
    protected $media = 'all';
    protected $src;

    public function __construct(array $data)
    {
        $this->src = $data['css'];
    }

    public function getType()
    {
        return self::TYPE_STYLE;
    }

    public function getSource()
    {

    }

    public function render()
    {
        return sprintf('<link rel="stylesheet" type="text/css" href="%s" media="%s"/>', $this->src, $this->media);
    }
}