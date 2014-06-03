<?php
namespace SimpleAsset;

use Swurl\Url;

class UrlRewriter
{
    private $cdnBaseUrl;

    public function __construct($cdnBaseUrl)
    {
        $this->cdnBaseUrl = $cdnBaseUrl;
    }

    public function rewriteCssUrls($content)
    {
        $urlRegex = '`url\((.*?)\)`s';
        $newContent = preg_replace_callback($urlRegex, function($matches) {
            $baseUrl = new Url($this->cdnBaseUrl);
            $existingUrl = new Url($matches[1]);

            $newUrl = new Url();
            $newUrl->setHost($baseUrl->getHost());
            if ($baseUrl->isSchemeless()) {
                $newUrl->makeSchemeless();
            } else {
                $newUrl->setScheme($baseUrl->getScheme());
            }

            if (count($baseUrl->getPath())) {
                $newUrl->setPath($baseUrl->getPath());
                $newUrl->getPath()->appendPath($existingUrl->getPath());
            } else {
                $newUrl->setPath($existingUrl->getPath());
            }


            $newUrl->getPath()->setEncoder(false);
            $newUrl->setQuery($existingUrl->getQuery());

            return "url(" . $newUrl->__toString() . ")";
        }, $content);

        return $newContent;
    }
}