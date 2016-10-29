<?php

namespace Wizkunde\SAMLBase\Response;

class LogoutResponse
{
    public function getLogoutData($xmlData)
    {
        $element = simplexml_load_string($xmlData);
        $element->registerXPathNamespace('samlp', 'urn:oasis:names:tc:SAML:2.0:protocol');
        $element->registerXPathNamespace('saml', 'urn:oasis:names:tc:SAML:2.0:assertion');

        $logoutData = array();
        $logoutData['id'] = (string) current($element->xpath('//samlp:SessionIndex'));
        $logoutData['sso_id'] = (string) current($element->xpath('//samlp:LogoutRequest/@ID'));
        $logoutData['username'] = (string) current($element->xpath('//saml:NameID'));

        return $logoutData;
    }
}

