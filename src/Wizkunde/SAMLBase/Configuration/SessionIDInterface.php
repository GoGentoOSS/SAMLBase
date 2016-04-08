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
    public function getNameIdFromDocument($xmlData);

    /**
     * @return string
     */
    public function getSessionIdFromDocument($xmlData);
}