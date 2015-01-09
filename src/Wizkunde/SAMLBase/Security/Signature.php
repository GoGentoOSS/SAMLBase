<?php

namespace Wizkunde\SAMLBase\Security;

use Wizkunde\SAMLBase\Certificate;

class Signature extends \XMLSecurityDSig implements SignatureInterface
{
    protected $certificate = null;
    protected $signingAlgorithm = '';

    public function verifyDOMDocument($document)
    {
        $signatureNode = $this->locateSignature($document);

        /**
         * No signature was added, it should not fail as this is not a requirement on redirect bindings
         */
        if (!$signatureNode) {
            return true;
        }

        $this->setCanonicalMethod(self::C14N);
        $this->addReference($document, $this->getSigningAlgorithm(), array('http://www.w3.org/2000/09/xmldsig#enveloped-signature', \XMLSecurityDSig::C14N), array('force_uri' => true));
    }

    public function setCertificate(Certificate $certificate)
    {
        $this->certificate = $certificate;
    }

    public function getCertificate()
    {
        return $this->certificate;
    }

    public function setSigningAlgorithm($algorithm)
    {
        $this->signingAlgorithm = $algorithm;
    }

    public function getSigningAlgorithm()
    {
        return $this->signingAlgorithm;
    }

    /**
     * Add the signature to the template
     *
     * @param \DOMElement $element
     * @return bool
     * @throws \Exception
     */
    public function addSignature(\DOMDocument $document)
    {
        $this->setCanonicalMethod(\XMLSecurityDSig::EXC_C14N_COMMENTS);
        $this->addReference($document, \XMLSecurityDSig::SHA1, array('http://www.w3.org/2000/09/xmldsig#enveloped-signature'));
        $this->add509Cert($this->getCertificate()->getPublicKey()->getX509Certificate());
        $this->sign($this->getCertificate()->getPrivateKey(), $document->firstChild);
    }
}