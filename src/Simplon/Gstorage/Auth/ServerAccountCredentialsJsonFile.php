<?php

namespace Simplon\Gstorage\Auth;

/**
 * Class ServerAccountCredentialsJsonFile
 * @package Simplon\Gstorage
 */
class ServerAccountServerAccountCredentialsJsonFile implements ServerAccountCredentialsInterface
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
     * @param string $jsonFile
     */
    public function __construct($jsonFile)
    {
        $data = file_get_contents($jsonFile);

        if ($data)
        {
            $this->clientEmail = $data['client_email'];
            $this->privateKey = $data['private_key'];
        }
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