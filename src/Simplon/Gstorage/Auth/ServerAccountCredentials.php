<?php

namespace Simplon\Gstorage\Auth;

/**
 * Class ServerAccountCredentials
 * @package Simplon\Gstorage
 */
class ServerAccountCredentials implements ServerAccountCredentialsInterface
{
    /**
     * @var string
     */
    private $clientEmail;

    /**
     * @var string
     */
    private $privateKey;

    /**
     * @param string $clientEmail
     * @param string $privateKey
     */
    public function loadFromParams($clientEmail, $privateKey)
    {
        $this->clientEmail = $clientEmail;
        $this->privateKey = $privateKey;
    }

    /**
     * @param string $jsonFile
     *
     * @return $this
     * @throws \Exception
     */
    public function loadFromJsonFile($jsonFile)
    {
        $data = file_get_contents($jsonFile);

        if ($data)
        {
            $data = json_decode($data, true);

            if (empty($data['client_email']) && empty($data['private_key']))
            {
                throw new \Exception('Missing "client_email" and "private_key" data within JSON data');
            }

            $this->clientEmail = $data['client_email'];
            $this->privateKey = $data['private_key'];
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getClientEmail()
    {
        return $this->clientEmail;
    }

    /**
     * @return string
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }
}