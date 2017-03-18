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
    public function getClientEmail(): string;

    /**
     * @return string
     */
    public function getPrivateKey(): string;
}