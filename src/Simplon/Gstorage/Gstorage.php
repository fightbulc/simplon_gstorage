<?php

namespace Simplon\Gstorage;

use Simplon\Gstorage\Auth\ServerAccountCredentialsInterface;
use Simplon\Gstorage\Data\ObjectData;
use Simplon\Gstorage\Data\UploadData;

/**
 * Class Gstorage
 * @package Simplon\Gstorage
 */
class Gstorage
{
    /**
     * @link https://developers.google.com/identity/protocols/googlescopes#autoscalerv1beta2
     */
    const SCOPE = 'https://www.googleapis.com/auth/devstorage.read_write';
    const URL_PUBLIC_DOMAIN = 'storage.googleapis.com';
    const URL_PUBLIC_PATTERN = 'http://{domain}/{bucket}/{fileName}';

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
     * @var null|string
     */
    private $urlCdn;

    /**
     * @param ServerAccountCredentialsInterface $credentials
     * @param string|null $urlCdn
     */
    public function __construct(ServerAccountCredentialsInterface $credentials, $urlCdn = null)
    {
        $this->credentials = $credentials;
        $this->urlCdn = $urlCdn;
    }

    /**
     * @param UploadData $data
     *
     * @return null|ObjectData
     */
    public function upload(UploadData $data)
    {
        $obj = new \Google_Service_Storage_StorageObject();
        $fileName = $this->buildRandomToken();

        $options = [
            'name'          => $fileName . '.' . $data->getFileExtension(),
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
            return new ObjectData($data->getBucket(), $obj->getName(), $this->urlCdn);
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
        $response = $this->getStorage()->objects->delete($data->getBucket(), $data->getFileName());

        return $response === null;
    }

    /**
     * @param string $url
     *
     * @return bool
     */
    public function isStorageUrl($url)
    {
        return strpos($url, self::URL_PUBLIC_DOMAIN) !== false;
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
            $this->client = new \Google_Client();
            $this->client->setAssertionCredentials($this->getCredentials());
        }

        return $this->client;
    }

    /**
     * @param int $length
     *
     * @return string
     */
    private function buildRandomToken($length = 24)
    {
        $randomString = '';
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        // generate token
        for ($i = 0; $i < $length; $i++)
        {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomString;
    }
}