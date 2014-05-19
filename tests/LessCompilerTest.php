<?php
use SimpleAsset\LessCompiler;

class LessCompilerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @runTestInSeparateProcess
     */
    public function testConstructorGetsCorrectLessCompilerPath()
    {
        $expectedLessCompilerPath = '/usr/local/bin/node /usr/local/bin/lessc';
        $lc = new LessCompiler($expectedLessCompilerPath);
        $rp = new ReflectionProperty($lc, 'lessCompilerPath');
        $rp->setAccessible(true);
        $this->assertEquals($expectedLessCompilerPath, $rp->getValue($lc));

        $expectedLessCompilerPath = `/usr/bin/env which lessc`;
        $lc = new LessCompiler();
        $this->assertEquals($expectedLessCompilerPath, $rp->getValue($lc));

        $expectedLessCompilerPath = '/usr/local/bin/node /usr/local/bin/lessc';
        define('LESS_COMPILER_PATH', $expectedLessCompilerPath);
        $lc = new LessCompiler();
        $this->assertEquals($expectedLessCompilerPath, $rp->getValue($lc));

    }

    public function testExceptionOnLesscError()
    {
        $lc = new LessCompiler();
        try {
            $lc->compile(__DIR__ . '/resources/lesserror.less', __DIR__ . '/workdir/lesserror.css');
            $this->fail('error compiling less should have thrown an exception');
        } catch (Exception $e) {
            $this->assertInstanceOf('\RuntimeException', $e);
        }
    }

    public function testCompileTriggeredOnMtime()
    {
        $sourceFile = __DIR__ . '/workdir/lesstest.less';
        $sourceImportFile = __DIR__ . '/workdir/lessimport.less';
        $destFile = __DIR__ . '/workdir/lesstest.css';

        @unlink($sourceFile);
        @unlink($sourceImportFile);
        @unlink($destFile);

        copy(__DIR__ . '/resources/lesstest.less', $sourceFile);
        copy(__DIR__ . '/resources/lessimport.less', $sourceImportFile);

        touch($sourceFile);
        $this->assertFalse(file_exists($destFile));

        sleep(1);

        $lc = new LessCompiler();
        $lc->compile($sourceFile, $destFile);
        $this->assertTrue(file_exists($destFile));
        $this->assertTrue(filemtime($sourceFile) < filemtime($destFile));

        sleep(1);
        file_put_contents($sourceFile, "\n\nh1 { color: black; }\n\n", FILE_APPEND);
        $this->assertTrue(filemtime($sourceFile) > filemtime($destFile));


        $lc->compile($sourceFile, $destFile);

        $resultContents = file_get_contents($destFile);
        $this->assertTrue(strpos($resultContents, 'h1') !== false);

        unlink($sourceFile);
        unlink($sourceImportFile);
        unlink($destFile);
    }

    public function testCompileTriggeredOnImportModification()
    {
        $sourceFile = __DIR__ . '/workdir/lesstest.less';
        $sourceImportFile = __DIR__ . '/workdir/lessimport.less';
        $destFile = __DIR__ . '/workdir/lesstest.css';

        @unlink($sourceFile);
        @unlink($sourceImportFile);
        @unlink($destFile);

        copy(__DIR__ . '/resources/lesstest.less', $sourceFile);
        copy(__DIR__ . '/resources/lessimport.less', $sourceImportFile);

        $this->assertFalse(file_exists($destFile));

        sleep(1);

        $lc = new LessCompiler();
        $lc->compile($sourceFile, $destFile);
        $this->assertTrue(file_exists($destFile));
        $this->assertTrue(filemtime($sourceFile) < filemtime($destFile));

        sleep(1);
        file_put_contents($sourceImportFile, "\n\nh2 { color: black; }\n\n", FILE_APPEND);
        $this->assertTrue(filemtime($sourceImportFile) > filemtime($destFile));


        $lc->compile($sourceFile, $destFile);

        $resultContents = file_get_contents($destFile);
        $this->assertTrue(strpos($resultContents, 'h2') !== false);

        unlink($sourceFile);
        unlink($sourceImportFile);
        unlink($destFile);
    }
}