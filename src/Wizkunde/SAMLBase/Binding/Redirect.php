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

    /**
     * Do a request with the current binding
     */
    public function request($requestType = 'AuthnRequest', $relayState = '')
    {
        parent::request($requestType);

        $this->setProtocolBinding(self::BINDING_REDIRECT);

	if($requestType == 'LogoutResponse') {
        	$targetUrl = (string)$this->buildRequestUrl() . '&SAMLResponse=' . $this->buildRequest($requestType);
	} else {
        	$targetUrl = (string)$this->buildRequestUrl() . '&SAMLRequest=' . $this->buildRequest($requestType);
	}

	if($relayState != '') {
		$targetUrl .= '&RelayState=' . $relayState;
	}

        header('Location: ' .$targetUrl );

        // Prevent any new headers overriding this one
        exit;
    }
}
