<?php

namespace GoGentoOSS\SAMLBase\Security;

use GoGentoOSS\SAMLBase\Certificate;

/**
 * Class Encryption
 * @package GoGentoOSS\SAMLBase\Security
 */
interface EncryptionInterface
{
    /**
     * Encrypt data with our certificate before we do anything with it
     *
     * @param $string
     */
    public function encrypt($string, $privateKey);

    /**
     * @param Certificate $certificate
     */
    public function setCertificate(Certificate $certificate);

    /**
     * @return mixed
     */
    public function getCertificate();

    /**
     * Decrypt incomming data with our certificate
     *
     * @param $string
     * @return \DOMDocument
     * @throws \Exception
     */
    public function decrypt($string);
}