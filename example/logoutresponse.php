<?php

if(!isset($_POST['SAMLResponse']) && !isset($_GET['SAMLResponse'])) {
    header('Location: attributes.php');
}

ini_set('display_errors', true);
include_once('../vendor/autoload.php');
include_once('container.php');

$SAMLResponse = (isset($_POST['SAMLResponse'])) ?  $_POST['SAMLResponse'] : $_GET['SAMLResponse'];
$responseData = $container->get('response')->decode($SAMLResponse);

echo $responseData->version;die;
