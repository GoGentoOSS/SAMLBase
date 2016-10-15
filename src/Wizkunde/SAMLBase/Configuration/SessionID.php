<?php

namespace Wizkunde\SAMLBase\Configuration;

/**
 * Class SessionID
 * @package Wizkunde\SAMLBase\Configuration
 */
class SessionID implements SessionIDInterface
{
    /**
     * @param string $document
     */
    public function getIdFromDocument($xmlData)
    {
        $element = simplexml_load_string($xmlData);
        $element->registerXPathNamespace('samlp', 'urn:oasis:names:tc:SAML:2.0:protocol');
        $element->registerXPathNamespace('saml', 'urn:oasis:names:tc:SAML:2.0:assertion');

        return (string) current($element->xpath('//saml:Subject/saml:NameID'));
    }

    public function getSessionIdFromDocument($xmlData)
    {
        $element = simplexml_load_string($xmlData);
        $element->registerXPathNamespace('samlp', 'urn:oasis:names:tc:SAML:2.0:protocol');
        $element->registerXPathNamespace('saml', 'urn:oasis:names:tc:SAML:2.0:assertion');

        return (string) current($element->xpath('//saml:AuthnStatement/@SessionIndex'));
    }
}
