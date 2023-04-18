<?php
/**
 * @author Ron van der Molen <ron@gogento.com>
 */
if(!isset($_POST['SAMLResponse']) && !isset($_GET['SAMLResponse']) && !isset($_REQUEST['SAMLart'])) {
    header('Location: attributes.php');
}

ini_set('display_errors', true);
include_once('../vendor/autoload.php');

include_once('container.php');

/**
 * Handle the response, if its an artifact, first resolve that!
 */
if(isset($_REQUEST['SAMLart'])) {
    $responseData = $container->get('samlbase_binding_artifact')
        ->setSettings($container->get('samlbase_idp_settings'))
        ->resolveArtifact($_REQUEST['SAMLart']);
} else if(isset($_REQUEST['SAMLResponse'])) {
    $responseData = $container->get('response')->decode($_REQUEST['SAMLResponse']);
}

$sessionId = new \GoGentoOSS\SAMLBase\Configuration\SessionID();
$sessionId = $sessionId->getSessionIdFromDocument($responseData);

session_start();
$_SESSION['sso_session_id'] = $sessionId;

$attributes = new \GoGentoOSS\SAMLBase\Claim\Attributes();

var_dump($attributes->getAttributes($responseData));
