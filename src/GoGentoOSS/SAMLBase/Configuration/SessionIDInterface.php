<?php

namespace GoGentoOSS\SAMLBase\Configuration;

/**
 * Interface SessionIDInterface
 * @package GoGentoOSS\SAMLBase\Configuration
 */
interface SessionIDInterface
{
    /**
     * @return string
     */
    public function getIdFromDocument($xmlData);
}