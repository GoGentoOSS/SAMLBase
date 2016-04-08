<?php

if(!isset($_POST['SAMLResponse']) && !isset($_GET['SAMLResponse']) && !isset($_REQUEST['SAMLart'])) {
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

// Artifact Resolution over SOAP
$container->register('samlbase_binding_artifact', 'Wizkunde\SAMLBase\Binding\Artifact')
    ->addMethodCall('setMetadata', array($metadata))
    ->addMethodCall('setTwigService', array(new Symfony\Component\DependencyInjection\Reference('twig')))
    ->addMethodCall('setUniqueIdService', array(new Symfony\Component\DependencyInjection\Reference('samlbase_unique_id_generator')))
    ->addMethodCall('setTimestampService', array(new Symfony\Component\DependencyInjection\Reference('samlbase_timestamp_generator')))
    ->addMethodCall('setSignatureService', array(new Symfony\Component\DependencyInjection\Reference('samlbase_signature')))
    ->addMethodCall('setHttpService', array(new Symfony\Component\DependencyInjection\Reference('guzzle_http')));

if(isset($_REQUEST['SAMLart'])) {
    $responseData = $container->get('samlbase_binding_artifact')
        ->setSettings($container->get('samlbase_idp_settings'))
        ->resolveArtifact($_REQUEST['SAMLart']);
} else if(isset($_REQUEST['SAMLResponse'])) {
    $responseData = $container->get('response')->decode($_REQUEST['SAMLResponse']);
}

$sessionId = new \Wizkunde\SAMLBase\Configuration\SessionID();
$sessionId = $sessionId->getSessionIdFromDocument($responseData);

session_start();
$_SESSION['sso_session_id'] = $sessionId;

$attributes = new \Wizkunde\SAMLBase\Claim\Attributes();

var_dump($attributes->getAttributes($responseData));
