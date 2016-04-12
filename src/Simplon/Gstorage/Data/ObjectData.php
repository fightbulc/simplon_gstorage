<?php

namespace Simplon\Gstorage\Data;

/**
 * Class ObjectData
 * @package Simplon\Gstorage\Data
 */
class ObjectData
{
    const URL_PUBLIC = 'http://storage.googleapis.com/{bucket}/{fileName}';

    /**
     * @var string
     */
    private $bucket;

    /**
     * @var string
     */
    private $fileName;

    /**
     * @param string $bucket
     * @param string $fileName
     */
    public function __construct($bucket, $fileName)
    {
        $this->bucket = $bucket;
        $this->fileName = $fileName;
    }

    /**
     * @return string
     */
    public function getUrlPublic()
    {
        return str_replace('{bucket}', $this->bucket, str_replace('{fileName}', $this->fileName, self::URL_PUBLIC));
    }

    /**
     * @return string
     */
    public function getBucket()
    {
        return $this->bucket;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }
}