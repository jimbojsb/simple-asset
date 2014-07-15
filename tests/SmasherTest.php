<?php
use SimpleAsset\Manager,
    SimpleAsset\Smasher,
    SimpleAsset\UrlRewriter;

class SmasherTest extends PHPUnit_Framework_TestCase
{
    public function testGenericSmash()
    {
        $manager = new Manager;
        $manager->setPublicRoot(__DIR__ . '/resources');
        $manager->define('test', function() {
            $this->style('/less/lesstest.less');
            $this->style('/lesstest.less');
            $this->script('/test.js');
        });
        $s = new Smasher($manager, __DIR__ . '/workdir', 1);
        $s->smash();
        $this->assertTrue(file_exists(__DIR__ . '/workdir/test.css'));
        $this->assertTrue(file_exists(__DIR__ . '/workdir/test.js'));
        @unlink(__DIR__ . '/workdir/test.css');
        @unlink(__DIR__ . '/workdir/test.js');
    }

    public function testSmashOneCollection()
    {
        $manager = new Manager;
        $manager->setPublicRoot(__DIR__ . '/resources');
        $manager->define('test', function() {
            $this->style('/less/lesstest.less');
            $this->style('/lesstest.less');
            $this->script('/test.js');
        });
        $s = new Smasher($manager, __DIR__ . '/workdir', 1);
        $s->smash(null, 'test');
        $this->assertTrue(file_exists(__DIR__ . '/workdir/test.css'));
        $this->assertTrue(file_exists(__DIR__ . '/workdir/test.js'));
        @unlink(__DIR__ . '/workdir/test.css');
        @unlink(__DIR__ . '/workdir/test.js');
    }

    public function testSmashWithUrlRewriter()
    {
        $manager = new Manager;
        $manager->setPublicRoot(__DIR__ . '/resources');
        $manager->define('test', function() {
            $this->style('/lesstest.less');
        });
        $s = new Smasher($manager, __DIR__ . '/workdir', 1);

        $urlRewriter = new UrlRewriter('http://example.com');

        $s->smash($urlRewriter);
        $this->assertTrue(file_exists(__DIR__ . '/workdir/test.css'));
        $content = file_get_contents(__DIR__ . '/workdir/test.css');
        $this->assertTrue(strpos($content, 'http://example.com') !== false);

        @unlink(__DIR__ . '/workdir/test.css');
    }
}