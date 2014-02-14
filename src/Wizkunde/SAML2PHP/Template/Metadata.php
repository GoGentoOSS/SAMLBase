<?php

namespace Wizkunde\SAML2PHP\Template;

use Wizkunde\SAML2PHP\Template\TeplateAbstract;
use Wizkunde\SAML2PHP\Configuration\Timestamp;

class Metadata extends TemplateAbstract
{
    public function __toString()
    {
        $this->timestamp->add(Timestamp::SECONDS_WEEK);

        // @todo some of these template settings like AssertionConsumerService needs to be configurable

        $template = <<<METADATA_TEMPLATE
<?xml version="1.0"?>
<md:EntityDescriptor xmlns:md="urn:oasis:names:tc:SAML:2.0:metadata"
                     validUntil="{$this->timestamp}"
                     entityID="{$this->getConfiguration()->getIssuer()}">
    <md:SPSSODescriptor protocolSupportEnumeration="urn:oasis:names:tc:SAML:2.0:protocol">
        <md:NameIDFormat>{$this->getConfiguration()->getNameIdFormat()}</md:NameIDFormat>
        <md:AssertionConsumerService Binding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST"
                                     Location="{$this->getConfiguration()->getSpReturnUrl()}"
                                     index="1"/>
    </md:SPSSODescriptor>
</md:EntityDescriptor>
METADATA_TEMPLATE;

        return $template;
    }
}
