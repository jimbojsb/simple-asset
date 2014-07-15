<?php
namespace SimpleAsset;

class Smasher
{
    private $manager;
    private $outputDir;
    private $cssMinifier;
    private $javascriptMinifier;

    public function __construct(Manager $manager, $outputDir)
    {
        $this->outputDir = $outputDir;
        $this->manager = $manager;
        $this->cssMinifier = new CssMinifier;
        $this->javascriptMinifier = new JavascriptMinifier;
    }

    public function smash(UrlRewriter $urlRewriter = null, $collection = null)
    {
        if ($collection) {
            $collections = [$this->manager->getCollection($collection)];
        } else {
            $collections = $this->manager->getCollections();
        }

        foreach ($collections as $collection) {
            $compiledStylesFile = $this->outputDir . '/' . $collection->getName() . ".tmp.css";
            $compiledScriptsFile = $this->outputDir . '/' . $collection->getName() . ".tmp.js";
            @unlink($compiledScriptsFile);
            @unlink(@$compiledStylesFile);

            $lessCompiler = new LessCompiler;

            $assetBuckets = $collection->getAssets();
            foreach ($assetBuckets as $bucket => $assets) {
                foreach ($assets as $asset) {
                    if (!$asset->isEmbedded() && !$asset->isRemote()) {
                        $sourcePath = $this->manager->getPublicRoot() . '/' . $asset->getSrc();
                        $appendData = file_get_contents($sourcePath);
                        $appendData .= "\n\n";
                        if ($asset instanceof StyleInterface) {
                            if ($asset->isLess()) {
                                $lessTmpFile = $this->outputDir . '/' . sha1($asset->getSrc() + mt_rand(0, mt_getrandmax())) . '.css';
                                $lessCompiler->compile($sourcePath, $lessTmpFile, true);
                                $appendData = file_get_contents($lessTmpFile) . "\n\n";
                                unlink($lessTmpFile);
                            }
                            file_put_contents($compiledStylesFile, $appendData, FILE_APPEND);
                        } else {
                            file_put_contents($compiledScriptsFile, $appendData, FILE_APPEND);
                        }
                    }
                }
            }


            if (file_exists($compiledStylesFile)) {
                if ($urlRewriter) {
                    $content = file_get_contents($compiledStylesFile);
                    $newContent = $urlRewriter->rewriteCssUrls($content);
                    file_put_contents($compiledStylesFile, $newContent);
                }
                $this->cssMinifier->minify($compiledStylesFile, str_replace('.tmp', '', $compiledStylesFile));
                @unlink($compiledStylesFile);

            }

            if (file_exists($compiledScriptsFile)) {
                $this->javascriptMinifier->minify($compiledScriptsFile, str_replace('.tmp', '', $compiledScriptsFile));
                @unlink($compiledScriptsFile);
            }
        }
    }
}