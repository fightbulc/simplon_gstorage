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
    private $urlCDN;

    /**
     * @param ServerAccountCredentialsInterface $credentials
     * @param null|string $urlCDN
     */
    public function __construct(ServerAccountCredentialsInterface $credentials, ?string $urlCDN = null)
    {
        $this->credentials = $credentials;
        $this->urlCDN = $urlCDN;
    }

    /**
     * @param UploadData $data
     *
     * @return null|ObjectData
     * @throws \Google_Exception
     */
    public function upload(UploadData $data): ?ObjectData
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

        if ($obj = $this->getStorage()->objects->insert($data->getBucket(), $obj, $options))
        {
            return new ObjectData($data->getBucket(), $obj->getName(), $this->urlCDN);
        }

        return null;
    }

    /**
     * @param ObjectData $data
     *
     * @return bool
     * @throws \Google_Exception
     */
    public function delete(ObjectData $data): bool
    {
        $response = $this->getStorage()->objects->delete(
            $data->getBucket(), $data->getFileName()
        );

        return $response === null;
    }

    /**
     * @param string $url
     *
     * @return bool
     */
    public function isStorageUrl(string $url): bool
    {
        return strpos($url, self::URL_PUBLIC_DOMAIN) !== false;
    }

    /**
     * @return \Google_Service_Storage
     * @throws \Google_Exception
     */
    private function getStorage(): \Google_Service_Storage
    {
        if (!$this->storage)
        {
            $this->storage = new \Google_Service_Storage($this->getClient());
        }

        return $this->storage;
    }

    /**
     * @return \Google_Client
     * @throws \Google_Exception
     */
    private function getClient(): \Google_Client
    {
        if (!$this->client)
        {
            $this->client = new \Google_Client();

            $this->client->setAuthConfig([
                'type'         => 'service_account',
                'client_id'    => $this->credentials->getClientEmail(),
                'client_email' => $this->credentials->getClientEmail(),
                'private_key'  => $this->credentials->getPrivateKey(),
            ]);

            $this->client->addScope([\Google_Service_Storage::DEVSTORAGE_READ_WRITE]);
        }

        return $this->client;
    }

    /**
     * @param int $length
     *
     * @return string
     */
    private function buildRandomToken(int $length = 24): string
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