<?php

namespace Simplon\Gstorage\Auth;

/**
 * Class ServerAccountCredentials
 * @package Simplon\Gstorage
 */
class ServerAccountServerAccountCredentials implements ServerAccountCredentialsInterface
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
    public function __construct($clientEmail, $privateKey)
    {
        $this->clientEmail = $clientEmail;
        $this->privateKey = $privateKey;
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