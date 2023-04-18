<?php
/**
 * @author Ron van der Molen <ron@gogento.com>
 */
namespace GoGentoOSS\SAMLBase\Security;

use GoGentoOSS\SAMLBase\Certificate;

/**
 * Interface SignatureInterface
 * @package GoGentoOSS\SAMLBase\Security
 */
interface SignatureInterface
{
    /**
     * @param Certificate $certificate
     * @return mixed
     */
    public function setCertificate(Certificate $certificate);

    /**
     * @return mixed
     */
    public function getCertificate();

    /**
     * @param $algorithm
     * @return mixed
     */
    public function setSigningAlgorithm($algorithm);

    /**
     * @return mixed
     */
    public function getSigningAlgorithm();

    /**
     * Add the signature to the template
     *
     * @param \DOMElement $element
     * @return void
     * @throws \Exception
     */
    public function addSignature(\DOMDocument $document);
}