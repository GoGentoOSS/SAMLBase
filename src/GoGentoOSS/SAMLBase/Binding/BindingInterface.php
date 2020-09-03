<?php

namespace GoGentoOSS\SAMLBase\Binding;
use GoGentoOSS\SAMLBase\Configuration\Settings;
use GoGentoOSS\SAMLBase\Configuration\Timestamp;
use GoGentoOSS\SAMLBase\Configuration\UniqueID;

/**
 * Class BindingInterface
 *
 * @package GoGentoOSS\SAMLBase\Binding
 */
interface BindingInterface
{
    /**
     * @return array
     */
    public function getSettings();

    /**
     * @return null
     */
    public function getSignatureService();

    /**
     * @param null $signatureService
     */
    public function setSignatureService($signatureService);

    /**
     * @return null
     */
    public function getTwigService();

    /**
     * @param null $twigService
     */
    public function setTwigService($twigService);

    /**
     * @return null
     */
    public function getUniqueIdService();

    /**
     * @param null $uniqueIdService
     */
    public function setUniqueIdService(UniqueID $uniqueIDService);

    /**
     * @return null
     */
    public function getTimestampService();

    /**
     * @param null $timestampService
     */
    public function setTimestampService(Timestamp $timestampService);


    /**
     * @param array $settings
     */
    public function setSettings(Settings $settings);

    public function setMetadata($metadata);

    /**
     * Do a request with the current binding
     */
    public function setTargetUrlFromMetadata();

    /**
     * @return target URL of our request
     */
    public function getTargetUrl();

    /**
     * Mandatory steps for all request binding subcalls
     */
    public function request();

    public function setProtocolBinding($binding);

    public function getProtocolBinding();

    public function buildRequest($requestType = 'AuthnRequest');
}
