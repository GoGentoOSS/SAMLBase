<?php

include_once('../vendor/autoload.php');

$certData = array();
$certData['passphrase'] = 'test1234';
$certData['certificate'] = file_get_contents('./cert/example.crt');
$certData['privatekey'] = file_get_contents('./cert/example.pem');

$container = new Symfony\Component\DependencyInjection\ContainerBuilder();
$container->setParameter('NameID', 'testNameId');
$container->setParameter('Issuer', 'http://saml.dev.wizkunde.nl/');
$container->setParameter('MetadataExpirationTime', 604800);
$container->setParameter('SPReturnUrl', 'http://return.wizkunde.nl/');
$container->setParameter('ForceAuthn', 'true');
$container->setParameter('IsPassive', 'false');
$container->setParameter('NameIDFormat', 'urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified');
$container->setParameter('ComparisonLevel', 'exact');

$container->register('twig_loader', 'Twig_Loader_Filesystem')->addArgument('../src/Wizkunde/SAMLBase/Template/Twig');
$container->register('twig', 'Twig_Environment')->addArgument(new Symfony\Component\DependencyInjection\Reference('twig_loader'));

$container->register('SigningCertificate', 'Wizkunde\SAMLBase\Certificate')
    ->addArgument($certData['privatekey'])
    ->addArgument($certData['certificate'])
    ->addArgument($certData['passphrase']);

$container->register('EncryptionCertificate', 'Wizkunde\SAMLBase\Certificate')
    ->addArgument($certData['privatekey'])
    ->addArgument($certData['certificate'])
    ->addArgument($certData['passphrase']);

$container->register('encryption', 'Wizkunde\SAMLBase\Security\Encryption')
    ->addMethodCall('setCertificate',array(new Symfony\Component\DependencyInjection\Reference('EncryptionCertificate')));

$container->register('signature', 'Wizkunde\SAMLBase\Security\Signature')
    ->addMethodCall('setCertificate',array(new Symfony\Component\DependencyInjection\Reference('SigningCertificate')));

$container->register('unique_id_generator', 'Wizkunde\SAMLBase\Configuration\UniqueID');
$container->register('timestamp_generator', 'Wizkunde\SAMLBase\Configuration\Timestamp');
/**
 * Setup the Metadata resolve service
 */
$container->register('guzzle_http', 'Guzzle\Http\Client');
$container->register('resolver', 'Wizkunde\SAMLBase\Metadata\ResolveService')
    ->addArgument(new Symfony\Component\DependencyInjection\Reference('guzzle_http'));

/**
 * Resolve the metadata
 */
$metadata = $container->get('resolver')->resolve(new \Wizkunde\SAMLBase\Metadata\IDPMetadata(), 'http://idp.wizkunde.nl/simplesaml/saml2/idp/metadata.php');

$container->register('binding_post', 'Wizkunde\SAMLBase\Binding\Redirect')
    ->addMethodCall('setMetadata', array($metadata))
    ->addMethodCall('setContainer', array($container));

$container->register('binding_redirect', 'Wizkunde\SAMLBase\Binding\Redirect')
    ->addMethodCall('setMetadata', array($metadata))
    ->addMethodCall('setContainer', array($container));

var_dump($container->get('binding_redirect')->request('AttributeQuery'));
