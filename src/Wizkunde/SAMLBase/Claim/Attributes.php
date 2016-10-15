<?php

namespace Wizkunde\SAMLBase\Claim;

class Attributes
{
    public function getAttributes($xmlData)
    {
        $element = simplexml_load_string($xmlData);
        $element->registerXPathNamespace('samlp', 'urn:oasis:names:tc:SAML:2.0:protocol');
        $element->registerXPathNamespace('saml', 'urn:oasis:names:tc:SAML:2.0:assertion');

        $attributes = array();
        foreach ($element->xpath('//saml:AttributeStatement/saml:Attribute') as $attribute) {
            $attributes[(string)$attribute->attributes()->Name] = (string)current($attribute->xpath('saml:AttributeValue'));
        }

        return $attributes;
    }
}
