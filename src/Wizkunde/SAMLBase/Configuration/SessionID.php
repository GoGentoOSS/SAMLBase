<?php

namespace Wizkunde\SAMLBase\Configuration;

/**
 * Class SessionID
 * @package Wizkunde\SAMLBase\Configuration
 */
class SessionID implements SessionIDInterface
{
    /**
     * Get the NameID, can be used as a backup for the session id, if that happens to be omitted
     * @param string $document
     */
    public function getNameIdFromDocument($xmlData)
    {
        $element = simplexml_load_string($xmlData);
        $element->registerXPathNamespace('samlp', 'urn:oasis:names:tc:SAML:2.0:protocol');
        $element->registerXPathNamespace('saml', 'urn:oasis:names:tc:SAML:2.0:assertion');

        return (string) current($element->xpath('//saml:Subject/saml:NameID'));
    }

    /**
     * Fetching the SSO Session id from the document, thats known to the IDP
     * @param $xmlData
     * @return string
     */
    public function getSessionIdFromDocument($xmlData)
    {
        $element = simplexml_load_string($xmlData);
        $element->registerXPathNamespace('samlp', 'urn:oasis:names:tc:SAML:2.0:protocol');
        $element->registerXPathNamespace('saml', 'urn:oasis:names:tc:SAML:2.0:assertion');

        return (string) current($element->xpath('//saml:AuthnStatement/@SessionIndex'));
    }
}
