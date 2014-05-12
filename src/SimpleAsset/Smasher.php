<?php
namespace SimpleAsset;

use SimpleAsset\CssMinifier;

class Smasher
{
    private $manager;
    private $outputDir;

    public function __construct(Manager $manager, $outputDir, $versionSuffix = 1)
    {
        $this->outputDir = $outputDir;
        $this->manager = $manager;
        $this->versionSuffix = $versionSuffix;
    }

    public function smash()
    {
        foreach ($this->manager->getCollections() as $collection) {
            $compiledStylesFile = $this->outputDir . '/' . $collection->getName() . "-$this->versionSuffix.tmp.css";
            $compiledScriptsFile = $this->outputDir . '/' . $collection->getName() . "-$this->versionSuffix.tmp.js";
            @unlink($compiledScriptsFile);
            @unlink(@$compiledStylesFile);

            $assetBuckets = $collection->getAssets();
            foreach ($assetBuckets as $bucket => $assets) {
                foreach ($assets as $asset) {
                    if (!$asset->isEmbedded()) {
                        $sourcePath = $this->manager->getPublicRoot() . '/' . $asset->getSrc();
                        $appendData = file_get_contents($sourcePath);
                        $appendData .= "\n\n";
                        if ($asset instanceof StyleInterface) {
                            if ($asset->isLess()) {
                                $lessTmpFile = $this->outputDir . '/' . sha1($asset->getSrc()) . '.css';
                                LessCompiler::compile($sourcePath, $lessTmpFile, true);
                                $appendData = file_get_contents($lessTmpFile) . "\n\n";
                                unlink($lessTmpFile);
                            }
                            file_put_contents($compiledStylesFile, $appendData, FILE_APPEND);
                            CssMinifier::minify($compiledStylesFile, str_replace('.tmp', '', $compiledStylesFile));
                            unlink($compiledStylesFile);
                        } else {
                            file_put_contents($compiledScriptsFile, $appendData, FILE_APPEND);
                        }
                    }
                }
            }
        }
    }
}