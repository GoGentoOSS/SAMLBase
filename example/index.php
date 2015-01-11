<?php

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
        'NameIDFormat' => 'testNameId',
        'ComparisonLevel' => 'exact',
        'OptionalURLParameters'   =>
            array(
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
$metadata = $container->get('resolver')->resolve($container->get('samlbase_metadata'), 'http://idp.wizkunde.nl/simplesaml/saml2/idp/metadata.php');

// POST Binding
$container->register('samlbase_binding_post', 'Wizkunde\SAMLBase\Binding\Post')
    ->addMethodCall('setMetadata', array($metadata))
    ->addMethodCall('setTwigService', array(new Symfony\Component\DependencyInjection\Reference('twig')))
    ->addMethodCall('setUniqueIdService', array(new Symfony\Component\DependencyInjection\Reference('samlbase_unique_id_generator')))
    ->addMethodCall('setTimestampService', array(new Symfony\Component\DependencyInjection\Reference('samlbase_timestamp_generator')))
    ->addMethodCall('setSignatureService', array(new Symfony\Component\DependencyInjection\Reference('samlbase_signature')))
    ->addMethodCall('setHttpService', array(new Symfony\Component\DependencyInjection\Reference('guzzle_http')));

// OR Redirect Binding
$container->register('samlbase_binding_redirect', 'Wizkunde\SAMLBase\Binding\Redirect')
    ->addMethodCall('setMetadata', array($metadata))
    ->addMethodCall('setTwigService', array(new Symfony\Component\DependencyInjection\Reference('twig')))
    ->addMethodCall('setUniqueIdService', array(new Symfony\Component\DependencyInjection\Reference('samlbase_unique_id_generator')))
    ->addMethodCall('setTimestampService', array(new Symfony\Component\DependencyInjection\Reference('samlbase_timestamp_generator')))
    ->addMethodCall('setSignatureService', array(new Symfony\Component\DependencyInjection\Reference('samlbase_signature')))
    ->addMethodCall('setHttpService', array(new Symfony\Component\DependencyInjection\Reference('guzzle_http')));

$redirectUrl = $container->get('samlbase_binding_redirect')
    ->setSettings($container->get('samlbase_idp_settings'))
    ->request();
