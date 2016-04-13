<?php

namespace Simplon\Gstorage\Data;

use Simplon\Gstorage\Gstorage;

/**
 * Class ObjectData
 * @package Simplon\Gstorage\Data
 */
class ObjectData
{
    /**
     * @var string
     */
    private $bucket;

    /**
     * @var string
     */
    private $fileName;

    /**
     * @var null|string
     */
    private $urlCdn;

    /**
     * @param string $bucket
     * @param string $fileName
     * @param string|null $urlCdn
     */
    public function __construct($bucket, $fileName, $urlCdn = null)
    {
        $this->bucket = $bucket;
        $this->fileName = $fileName;
        $this->urlCdn = $urlCdn;
    }

    /**
     * @return string
     */
    public function getUrlPublic()
    {
        if ($this->urlCdn)
        {
            return trim($this->urlCdn, '/') . '/' . $this->fileName;
        }

        $pattern = str_replace('{domain}', Gstorage::URL_PUBLIC_DOMAIN, Gstorage::URL_PUBLIC_PATTERN);
        $pattern = str_replace('{bucket}', $this->bucket, $pattern);
        $pattern = str_replace('{fileName}', $this->fileName, $pattern);

        return $pattern;
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