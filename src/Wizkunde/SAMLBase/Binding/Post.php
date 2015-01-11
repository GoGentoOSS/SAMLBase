<?php

namespace Wizkunde\SAMLBase\Binding;

/**
 * Class Redirect
 *
 * POST binding that uses HTTP-POST as a transport for a SAML request
 *
 * @package Wizkunde\SAMLBase\Binding
 */
class Post extends BindingAbstract
{
    /**
     * The location in the metadata that has the current bindings information
     * @var string
     */
    protected $metadataBindingLocation = 'SingleSignOnServicePost';

    /**
     * Do a request with the current binding
     */
    public function request($requestType = 'AuthnRequest')
    {
        parent::request();

        $this->setProtocolBinding(self::BINDING_POST);

        echo '<html><head></head><body onload="document.postform.submit();">';
        $form = $this->buildPostForm($requestType);
        echo $form;
        echo '</body></html>';
        exit;
    }

    protected function buildPostForm($requestType = 'AuthnRequest')
    {
        $form = '<form method="POST" action="' . $this->buildRequestUrl() . '" name="postform">';
        $form .= '<input type="hidden" name="SAMLRequest" value=" ' . (string) $this->buildRequest($requestType) . '">';
        $form .= '</form>';

        return $form;
    }
}
