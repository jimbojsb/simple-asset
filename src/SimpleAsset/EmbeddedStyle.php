<?php
namespace SimpleAsset;

class EmbeddedStyle
{
    protected $style;

    public function __construct($style)
    {
        $this->style = $style;
    }

    public function render()
    {
        $output = '<style type="text/css">' . "\n%s\n" . '</style>';
        return sprintf($output, $this->style);
    }
}