<?php
use SimpleAsset\Manager,
    SimpleAsset\Smasher;

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
}