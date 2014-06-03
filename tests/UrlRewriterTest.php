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

        $test = "background-image: url(http://foo.com/images/foo.jpg);";
        $expected = "background-image: url(https://www.test.com/images/foo.jpg);";
        $u = new UrlRewriter('https://www.test.com');
        $this->assertEquals($expected, $u->rewriteCssUrls($test));

        $test = "background: url(http://foo.com/images/foo.jpg);";
        $expected = "background: url(https://www.test.com/images/foo.jpg);";
        $u = new UrlRewriter('https://www.test.com');
        $this->assertEquals($expected, $u->rewriteCssUrls($test));
    }
}