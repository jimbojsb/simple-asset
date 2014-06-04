<?php
use SimpleAsset\UrlRewriter;

class UrlRewriterTest extends PHPUnit_Framework_TestCase
{
    public function testRewriteCssUrls()
    {
        $test = "background-image: url(/images/foo.jpg);";
        $expected = "background-image: url(http://www.test.com/images/foo.jpg);";
        $u = new UrlRewriter('http://www.test.com');
        $this->assertEquals($expected, $u->rewriteCssUrls($test));

        $test = "background-image: url(/images/foo.jpg);";
        $expected = "background-image: url(//www.test.com/images/foo.jpg);";
        $u = new UrlRewriter('//www.test.com');
        $this->assertEquals($expected, $u->rewriteCssUrls($test));
    }

    public function testRetinaImages()
    {
        $test = "background-image: url(/images/foo@2x.jpg);";
        $expected = "background-image: url(http://www.test.com/images/foo@2x.jpg);";
        $u = new UrlRewriter('http://www.test.com');
        $this->assertEquals($expected, $u->rewriteCssUrls($test));
    }

    public function testBaseUrlHasAPath()
    {
        $test = "background-image: url(/images/foo.jpg);";
        $expected = "background-image: url(http://www.test.com/test/images/foo.jpg);";
        $u = new UrlRewriter('http://www.test.com/test');
        $this->assertEquals($expected, $u->rewriteCssUrls($test));
    }

    public function testDontRewriteExistingAbsoluteUrls()
    {
        $test = "background-image: url(http://www.example.com/images/foo.jpg);";
        $u = new UrlRewriter('http://www.test.com/test');
        $this->assertEquals($test, $u->rewriteCssUrls($test));
    }
}