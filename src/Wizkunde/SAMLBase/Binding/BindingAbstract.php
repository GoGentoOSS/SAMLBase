<?php

namespace Wizkunde\SAMLBase\Binding;

use Wizkunde\SAMLBase\Configuration\Settings;
use Wizkunde\SAMLBase\Configuration\Timestamp;
use Wizkunde\SAMLBase\Configuration\UniqueID;

/**
 * Class BindingAbstract
 * @package Wizkunde\SAMLBase\Binding
 */
abstract class BindingAbstract implements BindingInterface
{
    protected $xpathSignatureNamespace = 'http://www.w3.org/2000/09/xmldsig#';

    /**
     *
     */
    const BINDING_REDIRECT = 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect';

    /**
     *
     */
    const BINDING_POST = 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST';

    /**
     *
     */
    const BINDING_ARTIFACT = 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Artifact';

    /**
     *
     */
    const BINDING_SOAP = 'urn:oasis:names:tc:SAML:2.0:bindings:SOAP';

    /**
     * @var null
     */
    protected $signatureService = null;
    /**
     * @var null
     */
    protected $twigService = null;
    /**
     * @var null
     */
    protected $uniqueIdService = null;
    /**
     * @var null
     */
    protected $timestampService = null;
    /**
     * @var null
     */
    protected $httpService = null;

    /**
     * @var Binding that we use for the current protocol
     */
    protected $protocolBinding = null;

    /**
     * The location in the metadata that has the current bindings information
     * @var string
     */
    protected $metadataBindingLocation = '';

    /**
     * The metadata thats used for the binding
     *
     * @var array
     */
    protected $metadata = array();

    /**
     * @var target URL for our request
     */
    protected $targetUrl = null;

    /**
     * Set the settings used for the connection
     *
     * @var array
     */
    protected $settings = null;

    /**
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @return null
     */
    public function getSignatureService()
    {
        return $this->signatureService;
    }

    /**
     * @param null $signatureService
     */
    public function setSignatureService($signatureService)
    {
        $this->signatureService = $signatureService;
    }

    /**
     * @return null
     */
    public function getHttpService()
    {
        return $this->httpService;
    }

    /**
     * @param null $httpService
     */
    public function setHttpService($httpService)
    {
        $this->httpService = $httpService;
    }

    /**
     * @return null
     */
    public function getTwigService()
    {
        return $this->twigService;
    }

    /**
     * @param null $twigService
     */
    public function setTwigService($twigService)
    {
        $this->twigService = $twigService;
    }

    /**
     * @return null
     */
    public function getUniqueIdService()
    {
        return $this->uniqueIdService;
    }

    /**
     * @param null $uniqueIdService
     */
    public function setUniqueIdService(UniqueID $uniqueIdService)
    {
        $this->uniqueIdService = $uniqueIdService;
    }

    /**
     * @return null
     */
    public function getTimestampService()
    {
        return $this->timestampService;
    }

    /**
     * @param null $timestampService
     */
    public function setTimestampService(Timestamp $timestampService)
    {
        $this->timestampService = $timestampService;
    }


    /**
     * @param array $settings
     */
    public function setSettings(Settings $settings)
    {
        $this->settings = $settings;

        return $this;
    }

    /**
     * @param $metadata
     * @return $this
     */
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * Do a request with the current binding
     */
    public function setTargetUrlFromMetadata($requestType = 'AuthnRequest')
    {
        $this->metadataBindingLocation = ($requestType == 'LogoutRequest') ? 'SingleLogoutService' :  $this->metadataBindingLocation;

        if ($this->metadataBindingLocation == '' || !isset($this->metadata[$this->metadataBindingLocation])) {
            throw new \Exception('Cant initialize binding, no SingleSignOn binding information is known for the current binding');
        }

        $this->targetUrl = $this->metadata[$this->metadataBindingLocation]['Location'];

        return $this;
    }

    /**
     * @return target URL of our request
     */
    public function getTargetUrl()
    {
        return $this->targetUrl;
    }

    /**
     * @return string
     */
    protected function buildRequestUrl()
    {
        $url = $this->getTargetUrl();

        $requestParameters = '';
        if(count($this->getSettings()->getValue('OptionalURLParameters')) > 0) {
            $requestParameters = '?' .  http_build_query($this->getSettings()->getValue('OptionalURLParameters'));
        }

        return $url . $requestParameters;
    }

    /**
     * Mandatory steps for all request binding subcalls
     */
    public function request($requestType = 'AuthnRequest')
    {
        $this->setTargetUrlFromMetadata($requestType);
    }

    /**
     * @param $binding
     * @return $this
     */
    public function setProtocolBinding($binding)
    {
        $this->protocolBinding = $binding;

        return $this;
    }

    /**
     * @return Binding
     */
    public function getProtocolBinding()
    {
        return $this->protocolBinding;
    }

    /**
     * @param string $requestType
     * @return string
     */
    public function buildRequest($requestType = 'AuthnRequest')
    {
        $settings = $this->getSettings()->getValues();

        $requestTemplate = $this->getTwigService()->render($requestType . '.xml.twig',
            array_merge($settings, array(
                'ProtocolBinding' => $this->getProtocolBinding(),
                'UniqueID' => $this->getUniqueIdService()->generate(),
                'Timestamp' => $this->getTimestampService()->generate()->toFormat(),
            ))
        );

        $signedTemplate = $this->signTemplate($requestTemplate);

        return $this->prepareTemplateForRequest($signedTemplate);
    }

    /**
     * @param $template
     * @return string
     */
    protected function signTemplate($template)
    {
        $document = new \DOMDocument();
        $document->loadXML($template);

        $this->getSignatureService()->addSignature($document);

        return $document->saveXML();
    }

    /**
     * @param $template
     * @return string
     */
    protected function prepareTemplateForRequest($template)
    {
        if($this->getProtocolBinding() == self::BINDING_POST) {
            return base64_encode($template);
        }

        $deflatedRequest = gzdeflate($template);
        $base64Request = base64_encode($deflatedRequest);
        $encodedRequest = urlencode($base64Request);

        return $encodedRequest;
    }


    /**
     * Generate an artifact that we can use to communicate to the IDP with
     *
     * @return string
     */
    protected function generateArtifact()
    {
        $typeCode = '0004';
        $endPointIndex = '0000';

        $artifact = '';
        for($i=0;$i<56;$i++) {
            $artifact .= mt_rand(0,9);
        }

        $deflatedRequest = gzdeflate($typeCode . $endPointIndex . $artifact);
        $base64Request = base64_encode($deflatedRequest);
        return urlencode($base64Request);
    }
}
