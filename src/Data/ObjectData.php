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
    private $urlCDN;

    /**
     * @param string $bucket
     * @param string $fileName
     * @param null|string $urlCDN
     */
    public function __construct(string $bucket, string $fileName, ?string $urlCDN = null)
    {
        $this->bucket = $bucket;
        $this->fileName = $fileName;
        $this->urlCDN = $urlCDN;
    }

    /**
     * @return string
     */
    public function getUrlPublic(): string
    {
        if ($this->urlCDN)
        {
            return trim($this->urlCDN, '/') . '/' . $this->fileName;
        }

        $pattern = str_replace('{domain}', Gstorage::URL_PUBLIC_DOMAIN, Gstorage::URL_PUBLIC_PATTERN);
        $pattern = str_replace('{bucket}', $this->bucket, $pattern);
        $pattern = str_replace('{fileName}', $this->fileName, $pattern);

        return $pattern;
    }

    /**
     * @return string
     */
    public function getBucket(): string
    {
        return $this->bucket;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }
}