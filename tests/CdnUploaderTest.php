<?php
use SimpleAsset\Manager,
    SimpleAsset\Smasher,
    SimpleAsset\CdnUploader;
use Aws\S3\S3Client;
use Guzzle\Plugin\Mock\MockPlugin;
use Guzzle\Http\Message\Response;


class CdnUploaderTest extends PHPUnit_Framework_TestCase
{
    public function testUpload()
    {
        $testBucketName = "simpleasset-test-bucket";

        if (file_exists(__DIR__ . '/.awscredentials.php')) {
            $awsCredentials = include __DIR__ . '/.awscredentials.php';
        } else {
            $this->markTestSkipped('Unable to do integration test without aws credentials');
            return;
        }

        $s3Client = S3Client::factory(array(
            'key'    => $awsCredentials['key'],
            'secret' => $awsCredentials['secret'],
        ));

        $s3Client->createBucket(array(
            'Bucket' => $testBucketName
        ));

        $m = new Manager;
        $m->setPublicRoot(__DIR__ . '/resources');
        $m->define('test', function() {
            $this->style('test.css');
            $this->script('test.js');
        });
        $smasher = new Smasher($m, __DIR__ . '/workdir');
        $smasher->smash();


        $c = new CdnUploader($s3Client, __DIR__ . '/workdir', $testBucketName, 'test');
        $c->generateCompressedVersions();
        $c->upload();

        $client = new Guzzle\Http\Client();
        $response = $client->get("http://$testBucketName.s3.amazonaws.com/test/test.css")->send();
        $this->assertTrue($response->isContentType('text/css'));

        $response = $client->get("http://$testBucketName.s3.amazonaws.com/test/test.js")->send();
        $this->assertTrue($response->isContentType('text/javascript'));

        $response = $client->get("http://$testBucketName.s3.amazonaws.com/test/test.gz.css")->send();
        $this->assertTrue($response->isContentType('text/css'));
        $this->assertEquals($response->getContentEncoding(), 'gzip');

        $response = $client->get("http://$testBucketName.s3.amazonaws.com/test/test.gz.js")->send();
        $this->assertTrue($response->isContentType('text/javascript'));
        $this->assertEquals($response->getContentEncoding(), 'gzip');

        $s3Client->deleteMatchingObjects($testBucketName, 'test');
        $s3Client->deleteBucket(array('Bucket' => $testBucketName));
    }

    public function testGenerateCompressedVersions()
    {
        $m = new Manager;
        $m->setPublicRoot(__DIR__ . '/resources');
        $m->define('test', function() {
            $this->style('test.css');
            $this->script('test.js');
        });
        $smasher = new Smasher($m, __DIR__ . '/workdir');
        $smasher->smash();
        $c = new CdnUploader(S3Client::factory(array(
            'key'    => 'foo',
            'secret' => 'bar'
        )), __DIR__ . '/workdir', null, null);
        $c->generateCompressedVersions();
        $this->assertTrue(file_exists(__DIR__ . '/workdir/test.gz.css'));
        $this->assertTrue(file_exists(__DIR__ . '/workdir/test.gz.js'));
        $this->assertTrue(file_exists(__DIR__ . '/workdir/test.css'));
        $this->assertTrue(file_exists(__DIR__ . '/workdir/test.js'));

        $fileCount = count(scandir(__DIR__ . '/workdir'));
        $this->assertEquals(7, $fileCount); // 4 files + ., .., and .gitkeep

        @unlink(__DIR__ . '/workdir/test.css');
        @unlink(__DIR__ . '/workdir/test.js');
        @unlink(__DIR__ . '/workdir/test.gz.css');
        @unlink(__DIR__ . '/workdir/test.gz.js');
    }

    public function tearDown()
    {
        $files = scandir(__DIR__ . '/workdir');
        foreach ($files as $file) {
            if (!(strpos($file, '.') === 0)) {
                unlink(__DIR__ . "/workdir/$file");
            }
        }
    }
}