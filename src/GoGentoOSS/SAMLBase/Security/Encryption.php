<?php

namespace GoGentoOSS\SAMLBase\Security;

use RobRichards\XMLSecLibs\XMLSecurityKey;
use GoGentoOSS\SAMLBase\Certificate;
use \RobRichards\XMLSecLibs\XMLSecEnc;

/**
 * Class Encryption
 * @package GoGentoOSS\SAMLBase\Security
 */
class Encryption extends XMLSecEnc implements EncryptionInterface
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
     * @param DOMDocument $element
     * @return DOMNode|null
     */
    public function locateEncryptedAssertion($element)
    {
        if ($element) {
            $xpath = new \DOMXPath($element);
            $query = "//xenc:EncryptedData";
            $nodeset = $xpath->query($query);
            return $nodeset->item(0);
        }
        return null;
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
        $document = new \DOMDocument();
        $document->loadXML($string);

        $encryptedData = $this->locateEncryptedData($document);

        if (!$encryptedData) {
            return false;
        }

        $this->setNode($encryptedData);

        $this->type = $encryptedData->getAttribute('Type');

        if (!$objKey = $this->locateKey($encryptedData)) {
            throw new \Exception("Unable to detect the algorithm");
        }

        if ($objKeyInfo = $this->locateKeyInfo($objKey)) {

            $inputKeyAlgo = $this->getCertificate()->getPrivateKey()->getAlgorith();
            if ($objKeyInfo->isEncrypted) {
                $symKeyInfoAlgo = $objKeyInfo->getAlgorith();

                if ($symKeyInfoAlgo === XMLSecurityKey::RSA_OAEP_MGF1P && $inputKeyAlgo === XMLSecurityKey::RSA_1_5) {
                    $inputKeyAlgo = XMLSecurityKey::RSA_OAEP_MGF1P;
                }

                $objencKey = $objKeyInfo->encryptedCtx;
                $objKeyInfo->key = $this->getCertificate()->getPrivateKey()->key;

                $keySize = $objKey->getSymmetricKeySize();
                if ($keySize === null) {
                    throw new \Exception('Unknown key size', true);
                }

                $key = $objencKey->decryptKey($objKeyInfo);
                if (strlen($key) != $keySize) {
                    throw new \Exception('Unexpected key size');
                }
                $objKey->loadkey($key);
            } else {
                $symKeyAlgo = $objKey->getAlgorith();
                if ($inputKeyAlgo !== $symKeyAlgo) {
                    throw new \Exception('Algorithm mismatch');
                }
                $objKey = $this->getCertificate()->getPrivateKey();
            }
        }

        $token = NULL;
        if ($decrypt = $this->decryptNode($objKey, true)) {
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

        return new \DOMDocument($output);
    }
}
