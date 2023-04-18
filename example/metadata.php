<?php
/**
 * @author Ron van der Molen <ron@gogento.com>
 */
ini_set('display_errors', true);

include_once('../vendor/autoload.php');
include_once('container.php');

$requestTemplate = $container->get('twig')->render('Metadata.xml.twig',
    array(
    'BaseURL'                   => 'https://your.serviceprovider.nl/',
    'ACSURL'                   => 'https://your.serviceprovider.nl/sso/acs',
    'SLOURL'                   => 'https://your.serviceprovider.nl/sso/logout',
    'EntityID'                  => 'https://your.serviceprovider.nl/',
    'ServiceProviderPublicKey'  => 'YourCertificatePublicKey',
    'OrganizationName'          => 'Your Service Provider',
    'OrganizationDisplayName'   => 'Your Service Provider',
    'OrganizationURL'           => 'https://your.serviceprovider.nl/',
    'ContactPersonSurName'      => 'Person LastName',
    'ContactPersonEmailAddress' => 'person@serviceprovider.nl'
    )
);

$document = new \DOMDocument();
$document->loadXML($requestTemplate);

$container->get('samlbase_signature')->signMetadata($document);

header('Content-Type: application/xml');
echo $document->saveXML();
exit;