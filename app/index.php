<?php
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/utils/simple-php-cacher/simple_php_cacher.php';
require_once __DIR__.'/utils/db.php';
require_once __DIR__.'/models/userprovider.php';
require_once __DIR__.'/models/userservice.php';
require_once __DIR__.'/models/modelbase.php';
require_once __DIR__.'/models/color.php';
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\User;


$app = new Silex\Application();

include(__DIR__.'/config/setup.php');

include(__DIR__.'/config/security.php');

include(__DIR__.'/config/routes.php');

$app->run();
?>
