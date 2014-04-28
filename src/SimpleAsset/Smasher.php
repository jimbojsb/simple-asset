<?php
namespace SimpleAsset;

use SimpleAsset\Minifier\CssMinifier,
    SimpleAsset\Minifier\JavascriptMinifier;

class Smasher
{
    private $manager;

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    public function smash($stack, $type)
    {
        $source = '';
        $assets = $this->manager->getAssets($stack, $type);
        foreach ($assets as $asset) {
            $source .= $asset->getSource() . "\n\n";
        }

        if ($type == 'styles') {
            $source = CssMinifier::minify($source);
        } else if ($type == 'scripts') {
            $source = JavascriptMinifer::minify($source);
        }

        return $source;
    }
}