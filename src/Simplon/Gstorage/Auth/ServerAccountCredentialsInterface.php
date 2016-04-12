<?php

namespace Simplon\Gstorage\Auth;

/**
 * Interface ServerAccountCredentialsInterface
 * @package Simplon\Gstorage
 */
interface ServerAccountCredentialsInterface
{
    /**
     * @return string
     */
    public function getClientEmail();

    /**
     * @return string
     */
    public function getPrivateKey();
}