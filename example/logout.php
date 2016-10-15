<?php

ini_set('display_errors', true);

session_start();

include_once('../vendor/autoload.php');
include_once('container.php');

$settings = $container->get('samlbase_idp_settings');

// Add our current login session id to the logout request
if(isset($_SESSION['idp_sessionid'])) {
    $settings->setValue('SessionIndex', $_SESSION['sso_session_id']);
}

$redirectUrl = $container->get('samlbase_binding_redirect')
    ->setSettings($settings)
    ->request('LogoutRequest');