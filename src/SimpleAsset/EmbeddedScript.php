<?php
namespace SimpleAsset;

class EmbeddedScript
{
    protected $script;

    public function __construct($script)
    {
        $this->script = $script;
    }

    public function getScript()
    {
        return $this->script;
    }

    public function render()
    {
        $output = '<script type="text/javascript">' . "\n%s\n" . '</script>';
        return sprintf($output, $this->script);
    }
}