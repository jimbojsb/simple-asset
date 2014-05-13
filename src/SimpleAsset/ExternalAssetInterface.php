<?php
namespace SimpleAsset;

interface ExternalAssetInterface
{
    public function getSrc();
    public function isRemote();
}