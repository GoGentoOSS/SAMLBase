<?php

namespace Wizkunde\SAMLBase\Metadata;

use Wizkunde\SAMLBase\Binding\Redirect;

class IDPMetadata extends MetadataAbstract
{
    /**
     * Mappings from the Identity Provider
     * @var array
     */
    protected $xpathMappings = array(
        'Signature' => array(
            '//ds:Signature/ds:SignedInfo/ds:SignatureMethod' => array(
                'Attributes' => array(
                    'Algorithm' => 'SigningMethod'
                ),
            ),

            '//ds:Signature/ds:SignatureValue' => array(
                'Value' => 'Signature'
            ),

            '//ds:Signature/ds:KeyInfo/ds:X509Data/ds:X509Certificate' => array(
                'Value' => 'Certificate'
            ),
        ),
        'Encryption' => array(
            '//ds:Signature/ds:SignedInfo/ds:Reference/ds:DigestMethod' => array(
                'Attributes' => array(
                    'Algorithm' => 'DigestMethod'
                ),
            ),
            // Digest Value of the digest we made
            '//ds:Signature/ds:SignedInfo/ds:Reference/ds:DigestValue' => array(
                'Value' => 'DigestValue'
            ),
        ),

        'Metadata' => array(
            '//{mdNs}IDPSSODescriptor' => array(
                'Attributes' => array(
                    'protocolSupportEnumeration' => 'Protocol'
                )
            ),

            '//{mdNs}IDPSSODescriptor/{mdNs}NameIDFormat' => array(
                'Value' => 'NameIDFormat'
            ),

            '//{mdNs}IDPSSODescriptor/{mdNs}KeyDescriptor[@use="signing"]/ds:KeyInfo/ds:X509Data/ds:X509Certificate' => array(
                'Value' => 'SignCertificate'
            ),

            '//{mdNs}IDPSSODescriptor/{mdNs}KeyDescriptor[@use="encryption"]/ds:KeyInfo/ds:X509Data/ds:X509Certificate' => array(
                'Value' => 'EncryptionCertificate'
            ),
        ),

        'SingleLogoutService' => array(
            '//{mdNs}IDPSSODescriptor/{mdNs}SingleLogoutService' => array(
                'Attributes' => array(
                    'Binding' => 'Binding',
                    'Location' => 'Location'
                ),
                'Multiple' => true
            ),
        ),

        'ArtifactResolutionService' => array(
            '//{mdNs}IDPSSODescriptor/{mdNs}ArtifactResolutionService' => array(
                'Attributes' => array(
                    'Binding' => 'Binding',
                    'Location' => 'Location'
                ),
                'Multiple' => true
            ),
        ),

        'SingleSignOnServiceRedirect' => array(
            '//{mdNs}IDPSSODescriptor/{mdNs}SingleSignOnService[@Binding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect"]' => array(
                'Attributes' => array(
                    'Binding' => 'Binding',
                    'Location' => 'Location'
                ),
                'Multiple' => true
            )
        ),

        'SingleSignOnServicePost' => array(
            '//{mdNs}IDPSSODescriptor/{mdNs}SingleSignOnService[@Binding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST"]' => array(
                'Attributes' => array(
                    'Binding' => 'Binding',
                    'Location' => 'Location'
                ),
                'Multiple' => true
            )
        )


    );
}