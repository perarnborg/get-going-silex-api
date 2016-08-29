<?php
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/utils/simple-php-cacher/simple_php_cacher.php';
require_once __DIR__.'/utils/db.php';
require_once __DIR__.'/security/userprovider.php';
require_once __DIR__.'/security/userservice.php';
require_once __DIR__.'/security/user.php';
require_once __DIR__.'/utils/api.php';
require_once __DIR__.'/models/modelbase.php';
require_once __DIR__.'/models/color.php';
require_once __DIR__.'/models/secret.php';
require_once __DIR__.'/controllers/usercontroller.php';
require_once __DIR__.'/controllers/colorcontroller.php';
require_once __DIR__.'/controllers/secretcontroller.php';

$app = new Silex\Application();

include(__DIR__.'/config/setup.php');

include(__DIR__.'/config/security.php');

include(__DIR__.'/config/routes.php');

$app->run();
?>
