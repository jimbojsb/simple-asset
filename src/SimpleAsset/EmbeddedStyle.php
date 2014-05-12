<?php
namespace SimpleAsset;

class EmbeddedStyle implements AssetInterface, StyleInterface
{
    protected $style;

    public function __construct($style)
    {
        $this->style = $style;
    }

    public function getStyle()
    {
        return $this->style;
    }

    public function isEmbedded()
    {
        return true;
    }

    public function render()
    {
        $output = '<style type="text/css">' . "\n%s\n" . '</style>';
        return sprintf($output, $this->style);
    }
}