<?php

include_once('../vendor/autoload.php');
include_once('container.php');

var_dump($container->get('binding_redirect')->request('AttributeQuery'));