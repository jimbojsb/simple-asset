<?php
namespace SimpleAsset;

use Aws\S3\Enum\CannedAcl;
use Aws\S3\S3Client;

class CdnUploader
{
    private $s3Client;
    private $sourceDir;
    private $destinationBucket;
    private $destinationDir;

    public function __construct(S3Client $s3Client, $sourceDir, $destinationBucket, $destinationDir)
    {
        $this->s3Client = $s3Client;
        $this->sourceDir = $sourceDir;
        $this->destinationBucket = $destinationBucket;
        $this->destinationDir = $destinationDir;
    }

    public function upload()
    {
        $this->generateCompressedVersions();
        $di = new \DirectoryIterator($this->sourceDir);
        /** @var \SplFileInfo $item */
        foreach ($di as $item) {
            if ($item->isFile()) {
                if ($item->getExtension() == 'js') {
                    $contentType = 'text/javascript';
                } else if ($item->getExtension() == 'css') {
                    $contentType = 'text/css';
                } else {
                    continue;
                }
                $objectData = array(
                    'ACL' => CannedAcl::PUBLIC_READ,
                    'Key' => $this->destinationDir . '/' . $item->getFilename(),
                    'Bucket' => $this->destinationBucket,
                    'Body' => file_get_contents($item->getPathname()),
                    'ContentType' => $contentType,
                    'CacheControl' => 'max-age=31536000',
                    'Expires' => time() + 31536000
                );
                if (strpos($item->getFilename(), '.gz.') !== false) {
                    $objectData['ContentEncoding'] = 'gzip';
                }
                $this->s3Client->putObject($objectData);
            }
        }
    }

    public function generateCompressedVersions()
    {
        $di = new \DirectoryIterator($this->sourceDir);
        /** @var \SplFileInfo $item */
        foreach ($di as $item) {
            if ($item->isFile()) {
                if (preg_match('`(\.css|\.js)`', $item->getFilename())) {
                    if (strpos($item->getFilename(), '.gz') === false) {
                        $compressedFilename = str_replace(array('.js', '.css'), array('.gz.js', '.gz.css'), $item->getPathName());
                        $contents = file_get_contents($item->getPathname());
                        file_put_contents($compressedFilename, gzencode($contents));
                    }
                }
            }
        }
    }
}