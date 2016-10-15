<?php

namespace Wizkunde\SAMLBase\Security;

use Wizkunde\SAMLBase\Certificate;

/**
 * Class Encryption
 * @package Wizkunde\SAMLBase\Security
 */
class Encryption extends \XMLSecEnc implements EncryptionInterface
{
    protected $certificate = null;

    /**
     * Encrypt data with our certificate before we do anything with it
     *
     * @param $string
     */
    public function encrypt($string, $privateKey)
    {
        $document = new \DOMDocument($string);
    }

    /**
     * @param Certificate $certificate
     */
    public function setCertificate(Certificate $certificate)
    {
        $this->certificate = $certificate;
    }

    /**
     * @return mixed
     */
    public function getCertificate()
    {
        return $this->certificate;
    }

    /**
     * Decrypt incomming data with our certificate
     *
     * @param $string
     * @return \DOMDocument
     * @throws \Exception
     */
    public function decrypt($string)
    {
        $document = new \DOMDocument($string);

        $encryptedData = $this->locateEncryptedData($document);

        /**
         * If data was not transmitted encrypted (happens a lot with redirect binding)
         * Then return the document
         */
        if (!$encryptedData) {
            return $document;
        }

        $this->setNode($string);

        $this->type = $encryptedData->getAttribute("Type");
        if (!$objKey = $this->locateKey()) {
            throw new \Exception("Unable to detect the algorithm");
        }

        if ($objKeyInfo = $this->locateKeyInfo($objKey)) {
            if ($objKeyInfo->isEncrypted) {
                $objencKey = $objKeyInfo->encryptedCtx;
                $objKeyInfo->loadKey($this->getCertificate()->getPublicKey());
                $key = $objencKey->decryptKey($objKeyInfo);
            }
        }

        if (!$objKey->key && empty($key)) {
            $objKey->loadKey($this->getCertificate()->getPublicKey());
        }

        if (empty($objKey->key)) {
            $objKey->loadKey($key);
        }

        $token = NULL;
        if ($decrypt = $this->decryptNode($objKey, TRUE)) {
            $output = NULL;
            if ($decrypt instanceof \DOMNode) {
                if ($decrypt instanceof \DOMDocument) {
                    $output = $decrypt->saveXML();
                } else {
                    $output = $decrypt->ownerDocument->saveXML();
                }
            } else {
                $output = $decrypt;
            }
        }

        return new \DOMDocument($string);
    }
}
