<?php
use Symfony\Component\HttpFoundation\Request;

$app->get('/', function() use($app) {
  return $app->redirect('/colors');
});

$app->get('/colors', function() use($app) {
  $colorsService = new Color($app);
  $colors = $colorsService->index();

  return json_encode($colors);
});

$app->get('/colors/{id}', function (Silex\Application $app, $id) {
  $colorsService = new Color($app);
  $color = $colorsService->get($id);

  if (!$color) {
     $app->abort(404, "id {$id} does not exist.");
  }
  return json_encode($color);
});

$app->get('/colors/user', function (Silex\Application $app, $id) {
  $colorsService = new Color($app);
  $color = $colorsService->listForUser(UserService::current($app)->id, $id);

  if (!$color) {
     $app->abort(404, "id {$id} does not exist.");
  }
  return json_encode($color);
});

$app->get('/colors/user/{id}', function (Silex\Application $app, $id) {
  $colorsService = new Color($app);
  $color = $colorsService->getForUser(UserService::current($app)->id, $id);

  if (!$color) {
     $app->abort(404, "id {$id} does not exist.");
  }
  return json_encode($color);
});

$app->get('/user', function() use($app) {
  return json_encode(UserService::current($app));
});

$app->get('/user/login', function(Request $request) use ($app) {
    return $app['twig']->render('login.html', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
});

$app->post('/user/register', function() use($app) {
  $username = isset($_GET['username']) ? $_GET['username'] : null;
  $password = isset($_GET['password']) ? $_GET['password'] : null;
  if($username && $password) {
    return json_encode(UserService::register($app, $username, 'ROLE_USER', $password));
  }
  $app->abort(403, "no credentials.");
});
