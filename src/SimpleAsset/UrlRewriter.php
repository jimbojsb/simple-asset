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
            $existingUrl->setHost($baseUrl->getHost());
            if ($baseUrl->isSchemeless()) {
                $existingUrl->makeSchemeless();
            } else {
                $existingUrl->setScheme($baseUrl->getScheme());
            }
            $existingUrl->getPath()->setEncoder(false);
            return "url(" . $existingUrl->__toString() . ")";
        }, $content);

        return $newContent;
    }
}