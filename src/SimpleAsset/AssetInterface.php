<?php
namespace SimpleAsset;

interface AssetInterface
{
    public function render();
    public function isEmbedded();
}