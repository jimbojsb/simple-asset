<?php
namespace SimpleAsset\Asset;

interface AssetInterface
{
    const TYPE_STYLE = 'style';
    const TYPE_SCRIPT = 'script';

    public function render();
    public function getSource();
    public function getType();
}