<?php

namespace Simplon\Gstorage;

use Simplon\Gstorage\Auth\ServerAccountCredentialsInterface;
use Simplon\Gstorage\Data\ObjectData;
use Simplon\Gstorage\Data\UploadData;

/**
 * Class GoogleStorage
 * @package Simplon\Gstorage
 */
class GoogleStorage
{
    const SCOPE = 'https://www.googleapis.com/auth/devstorage.read_write';

    /**
     * @var ServerAccountCredentialsInterface
     */
    private $credentials;

    /**
     * @var \Google_Client
     */
    private $client;

    /**
     * @var \Google_Service_Storage
     */
    private $storage;

    /**
     * @param ServerAccountCredentialsInterface $credentials
     */
    public function __construct(ServerAccountCredentialsInterface $credentials)
    {
        $this->credentials = $credentials;
    }

    /**
     * @param UploadData $data
     *
     * @return null|ObjectData
     */
    public function upload(UploadData $data)
    {
        $obj = new \Google_Service_Storage_StorageObject();
        $obj->setName($data->getFileName());

        $options = [
            'name'          => $data->getFileName(),
            'data'          => $data->getBlob(),
            'uploadType'    => 'media',
            'mimeType'      => $data->getMimeType(),
            'predefinedAcl' => $data->isPublic() ? 'publicread' : null,
        ];

        $obj = $this->getStorage()->objects->insert(
            $data->getBucket(), $obj, $options
        );

        if ($obj)
        {
            return new ObjectData($data->getBucket(), $obj->getName());
        }

        return null;
    }

    /**
     * @param ObjectData $data
     *
     * @return bool
     */
    public function delete(ObjectData $data)
    {
        if ($this->getStorage()->objects->delete($data->getBucket(), $data->getFileName()))
        {
            return true;
        }

        return false;
    }

    /**
     * @return \Google_Service_Storage
     */
    private function getStorage()
    {
        if ($this->storage === null)
        {
            $this->storage = new \Google_Service_Storage($this->getClient());
        }

        return $this->storage;
    }

    /**
     * @return \Google_Auth_AssertionCredentials
     */
    private function getCredentials()
    {
        return new \Google_Auth_AssertionCredentials($this->credentials->getClientEmail(), [self::SCOPE], $this->credentials->getPrivateKey());
    }

    /**
     * @return \Google_Client
     */
    private function getClient()
    {
        if ($this->client === null)
        {
            $client = new \Google_Client();
            $client->setAssertionCredentials($this->getCredentials());
        }

        return $this->client;
    }
}