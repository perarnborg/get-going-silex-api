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

$app->get('/secrets/user', function (Silex\Application $app) {
  $secretsService = new Secret($app);
  $secrets = $secretsService->indexForUser(UserService::current($app)->id);

  return json_encode($secrets);
});

$app->get('/secrets/user/{id}', function (Silex\Application $app, $id) {
  $secretsService = new Secret($app);
  $secret = $secretsService->getForUser(UserService::current($app)->id, $id);

  if (!$secret) {
     $app->abort(404, "id {$id} does not exist.");
  }
  return json_encode($secret);
});

$app->post('/secrets', function (Silex\Application $app) {
  $secretsService = new Secret($app);
  $row = $secretsService->getRow($_POST);
  $res = $secretsService->save($row, UserService::current($app));

  return json_encode($res);
});

$app->post('/secrets/{id}', function (Silex\Application $app, $id) {
  $secretsService = new Secret($app);
  $row = $secretsService->getRow($_POST, $id);
  $res = $secretsService->save($row, UserService::current($app));

  return json_encode($res);
});

$app->delete('/secrets/{id}', function (Silex\Application $app, $id) {
  $secretsService = new Secret($app);
  $affected = $secretsService->delete($id, UserService::current($app));

  return json_encode($secrets);
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
