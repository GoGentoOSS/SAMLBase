<?php

if(!isset($_POST['SAMLResponse']) && !isset($_GET['SAMLResponse'])) {
    header('Location: attributes.php');
}

ini_set('display_errors', true);
include_once('../vendor/autoload.php');

$container = new Symfony\Component\DependencyInjection\ContainerBuilder();

$container->register('twig_loader', 'Twig_Loader_Filesystem')->addArgument('../src/Wizkunde/SAMLBase/Template/Twig');
$container->register('twig', 'Twig_Environment')->addArgument(new Symfony\Component\DependencyInjection\Reference('twig_loader'));

$container->register('guzzle_http', 'Guzzle\Http\Client');

$container->register('SigningCertificate', 'Wizkunde\SAMLBase\Certificate')
    ->addMethodCall('setPassphrase', array('test1234'))
    ->addMethodCall('setPublicKey', array('cert/example.crt', true))
    ->addMethodCall('setPrivateKey', array('cert/example.pem', true));

$container->register('EncryptionCertificate', 'Wizkunde\SAMLBase\Certificate')
    ->addMethodCall('setPassphrase', array('test1234'))
    ->addMethodCall('setPublicKey', array('./cert/example.crt', true))
    ->addMethodCall('setPrivateKey', array('./cert/example.pem', true));

$container->register('samlbase_idp_settings', 'Wizkunde\SAMLBase\Configuration\Settings')
    ->addMethodCall('setValues', array(array(
        'NameID' => 'testNameId',
        'Issuer' => 'http://saml.dev.wizkunde.nl/',
        'MetadataExpirationTime' => 604800,
        'SPReturnUrl' => 'http://return.wizkunde.nl/',
        'ForceAuthn' => 'true',
        'IsPassive' => 'false',
        'NameIDFormat' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified',
        'ComparisonLevel' => 'exact',
        'OptionalURLParameters'   => array(
            'source' => 'saml'
        )
    )));

$container->register('samlbase_encryption', 'Wizkunde\SAMLBase\Security\Encryption')
    ->addMethodCall('setCertificate',array(new Symfony\Component\DependencyInjection\Reference('EncryptionCertificate')));

$container->register('samlbase_signature', 'Wizkunde\SAMLBase\Security\Signature')
    ->addMethodCall('setCertificate',array(new Symfony\Component\DependencyInjection\Reference('SigningCertificate')));

$container->register('samlbase_unique_id_generator', 'Wizkunde\SAMLBase\Configuration\UniqueID');
$container->register('samlbase_timestamp_generator', 'Wizkunde\SAMLBase\Configuration\Timestamp');

/**
 * Setup the Metadata resolve service
 */
$container->register('resolver', 'Wizkunde\SAMLBase\Metadata\ResolveService')
    ->addArgument(new Symfony\Component\DependencyInjection\Reference('guzzle_http'));

$container->register('samlbase_metadata', 'Wizkunde\SAMLBase\Metadata\IDPMetadata');

/**
 * Resolve the metadata
 */
$metadata = $container->get('resolver')->resolve(new \Wizkunde\SAMLBase\Metadata\IDPMetadata(), 'http://idp.wizkunde.nl/simplesaml/saml2/idp/metadata.php');


$container->register('response', 'Wizkunde\SAMLBase\Response\AuthnResponse')
    ->addMethodCall('setSignatureService', array(new Symfony\Component\DependencyInjection\Reference('samlbase_signature')))
    ->addMethodCall('setEncryptionService', array(new Symfony\Component\DependencyInjection\Reference('samlbase_encryption')));

$SAMLResponse = (isset($_POST['SAMLResponse'])) ?  $_POST['SAMLResponse'] : $_GET['SAMLResponse'];
$responseData = $container->get('response')->decode($SAMLResponse);

echo $responseData->version;die;
