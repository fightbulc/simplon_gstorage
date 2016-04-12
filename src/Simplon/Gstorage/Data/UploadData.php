<?php

namespace Simplon\Gstorage\Data;

/**
 * Class UploadData
 * @package Simplon\Gstorage\Data
 */
class UploadData
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
     * @var string
     */
    private $blob;

    /**
     * @var string
     */
    private $mimeType;

    /**
     * @var bool
     */
    private $isPublic = false;

    /**
     * @param string $bucket
     */
    public function __construct($bucket)
    {
        $this->bucket = $bucket;
    }

    /**
     * @param string $file
     * @param bool $isPublic
     */
    public function loadWithFile($file, $isPublic = true)
    {
        $paths = explode('/', $file);
        $this->fileName = array_pop($paths);
        $this->blob = file_get_contents($file);
        $this->isPublic = $isPublic;
    }

    /**
     * @param string $fileName
     * @param string $blob
     * @param bool $isPublic
     */
    public function loadWithBlob($fileName, $blob, $isPublic = true)
    {
        $this->fileName = $fileName;
        $this->blob = $blob;
        $this->isPublic = $isPublic;
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

    /**
     * @return string
     */
    public function getBlob()
    {
        return $this->blob;
    }

    /**
     * @return string
     */
    public function getMimeType()
    {
        if ($this->mimeType === null)
        {
            $this->mimeType = (new \finfo(FILEINFO_MIME))->buffer($this->blob);

            if (strpos($this->mimeType, ';') !== false)
            {
                $parts = explode(';', $this->mimeType);
                $this->mimeType = array_shift($parts);
            }
        }

        return $this->mimeType;
    }

    /**
     * @return boolean
     */
    public function isPublic()
    {
        return $this->isPublic === true;
    }
}