<?php
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/utils/simple-php-cacher/simple_php_cacher.php';
require_once __DIR__.'/utils/db.php';
require_once __DIR__.'/utils/dbservicelayer.php';
require_once __DIR__.'/model/modelbase.php';
require_once __DIR__.'/model/color.php';

$app = new Silex\Application();
// production environment - false; test environment - true
$app['debug'] = true;

$app->get('/colors', function() use($app) {
  $colorsService = new Color($app);
  $colors = $colorsService->index();

  return json_encode($colors);
});

$app->get('/colors/{id}', function (Silex\Application $app, $id) {
  $colorsService = new Color($app);
  $user = $colorsService->get($id);

  if (!$user) {
     $app->abort(404, "id {$id} does not exist.");
  }
  return json_encode($user);
});

$app->run();
?>
