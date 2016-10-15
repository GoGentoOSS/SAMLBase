<?php

ini_set('display_errors', true);

include_once('../vendor/autoload.php');
include_once('container.php');

$redirectUrl = $container->get('samlbase_binding_redirect')
    ->setSettings($container->get('samlbase_idp_settings'))
    ->request();