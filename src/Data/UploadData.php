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
    public function __construct(string $bucket)
    {
        $this->bucket = $bucket;
    }

    /**
     * @param string $file
     * @param bool $isPublic
     *
     * @return UploadData
     */
    public function loadWithFile(string $file, bool $isPublic = true): self
    {
        $paths = explode('/', $file);
        $this->fileName = array_pop($paths);
        $this->blob = file_get_contents($file);
        $this->isPublic = $isPublic;

        return $this;
    }

    /**
     * @param string $fileName
     * @param string $blob
     * @param bool $isPublic
     *
     * @return UploadData
     */
    public function loadWithBlob(string $fileName, string $blob, bool $isPublic = true): self
    {
        $this->fileName = $fileName;
        $this->blob = $blob;
        $this->isPublic = $isPublic;

        return $this;
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

    /**
     * @return string
     */
    public function getFileExtension(): string
    {
        $parts = explode('.', $this->fileName);
        $ext = array_pop($parts);

        return strtolower($ext);
    }

    /**
     * @return string
     */
    public function getBlob(): string
    {
        return $this->blob;
    }

    /**
     * @return string
     */
    public function getMimeType(): string
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
    public function isPublic(): bool
    {
        return $this->isPublic === true;
    }
}