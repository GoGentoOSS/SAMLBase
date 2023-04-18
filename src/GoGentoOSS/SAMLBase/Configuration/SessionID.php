<?php
/**
 * @author Ron van der Molen <ron@gogento.com>
 */
namespace GoGentoOSS\SAMLBase\Configuration;

/**
 * Class SessionID
 * @package GoGentoOSS\SAMLBase\Configuration
 */
class SessionID implements SessionIDInterface
{
    /**
     * @param $xmlData
     * @return string
     */
    public function getIdFromDocument($xmlData)
    {
        $element = simplexml_load_string($xmlData);
        $element->registerXPathNamespace('samlp', 'urn:oasis:names:tc:SAML:2.0:protocol');
        $element->registerXPathNamespace('saml', 'urn:oasis:names:tc:SAML:2.0:assertion');

        return (string) current($element->xpath('//saml:Subject/saml:NameID'));
    }

    /**
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
