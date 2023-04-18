<?php
/**
 * @author Ron van der Molen <ron@gogento.com>
 */
namespace GoGentoOSS\SAMLBase\Binding;

/**
 * Class Redirect
 *
 * POST binding that uses HTTP-POST as a transport for a SAML request
 *
 * @package GoGentoOSS\SAMLBase\Binding
 */
class Post extends BindingAbstract
{
    /**
     * The location in the metadata that has the current bindings information
     * @var string
     */
    protected $metadataBindingLocation = 'SingleSignOnServicePost';
    protected $metadataSLOLocation = 'SingleLogoutServicePost';

    /**
     * Do a request with the current binding
     */
    public function request($requestType = 'AuthnRequest')
    {
        parent::request($requestType);

        $this->setProtocolBinding(self::BINDING_POST);

        echo '<html><head></head><body onload="document.postform.submit();">';
        $form = $this->buildPostForm($requestType);
        echo $form;
        echo '</body></html>';
        exit;
    }

    /**
     * Building the post form to submit a SAML post request
     *
     * @param $requestType
     * @return string
     */
    protected function buildPostForm($requestType = 'AuthnRequest')
    {
        $requestParam = ($requestType == 'LogoutResponse') ? 'SAMLResponse' : 'SAMLRequest';

        $form = '<form method="POST" action="' . $this->buildRequestUrl() . '" name="postform">';
        $form .= '<input type="hidden" name="' . $requestParam . '" value=" ' . (string) $this->buildRequest($requestType) . '">';
        $form .= '</form>';

        return $form;
    }
}
