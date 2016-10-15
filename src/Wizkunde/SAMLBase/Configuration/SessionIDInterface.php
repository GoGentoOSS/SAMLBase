<?php

namespace Wizkunde\SAMLBase\Configuration;

/**
 * Interface SessionIDInterface
 * @package Wizkunde\SAMLBase\Configuration
 */
interface SessionIDInterface
{
    /**
     * @return string
     */
    public function getIdFromDocument($xmlData);
}