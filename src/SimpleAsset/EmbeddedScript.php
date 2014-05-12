<?php
namespace SimpleAsset;

class EmbeddedScript implements AssetInterface, ScriptInterface
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

    public function isEmbedded()
    {
        return true;
    }

    public function render()
    {
        $output = '<script type="text/javascript">' . "\n%s\n" . '</script>';
        return sprintf($output, $this->script);
    }
}