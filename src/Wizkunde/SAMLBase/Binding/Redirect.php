<?php

namespace Wizkunde\SAMLBase\Binding;

/**
 * Class Redirect
 *
 * Redirect binding that uses HTTP-GET as a transport for a SAML request
 *
 * @package Wizkunde\SAMLBase\Binding
 */
class Redirect extends BindingAbstract
{
    /**
     * The location in the metadata that has the current bindings information
     * @var string
     */
    protected $metadataBindingLocation = 'SingleSignOnServiceRedirect';
    protected $metadataSLOLocation = 'SingleLogoutServiceRedirect';

    /**
     * Do a request with the current binding
     */
    public function request($requestType = 'AuthnRequest', $relayState = '')
    {
        parent::request($requestType);

        $this->setProtocolBinding(self::BINDING_REDIRECT);

        $separator = (strpos((string)$this->buildRequestUrl(), '?') > 0) ? '&' : '?';

        if($requestType == 'LogoutResponse') {
                $targetUrl = (string)$this->buildRequestUrl() . $separator . 'SAMLResponse=' . $this->buildRequest($requestType);
        } else {
                $targetUrl = (string)$this->buildRequestUrl() . $separator . 'SAMLRequest=' . $this->buildRequest($requestType);
        }

        if($relayState != '') {
            $targetUrl .= '&RelayState=' . $relayState;
        }

        header('Location: ' .$targetUrl );

        exit;
    }
}
