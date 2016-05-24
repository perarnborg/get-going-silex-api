<?php
use Symfony\Component\HttpFoundation\Request;

$app->get('/', function() use($app) {
  return $app->redirect('/colors');
});

$app->get('/colors', function() use($app) {
  $colorsService = new Color($app);
  $colors = $colorsService->index();

  return Api::respond($colors);
});

$app->get('/colors/{id}', function (Silex\Application $app, $id) {
  $colorsService = new Color($app);
  $color = $colorsService->get($id);

  if (!$color) {
     $app->abort(404, "id {$id} does not exist.");
  }
  return Api::respond($color);
});

$app->get('/secrets/user', function (Silex\Application $app) {
  $secretsService = new Secret($app);
  $secrets = $secretsService->indexForUser(UserService::current($app)->id);

  return Api::respond($secrets);
});

$app->get('/secrets/user/{id}', function (Silex\Application $app, $id) {
  $secretsService = new Secret($app);
  $secret = $secretsService->getForUser(UserService::current($app)->id, $id);

  if (!$secret) {
     $app->abort(404, "id {$id} does not exist.");
  }
  return Api::respond($secret);
});

$app->post('/secrets', function (Silex\Application $app) {
  try {
    $secretsService = new Secret($app);
    $row = $secretsService->getRow($_POST, UserService::current($app));
    $secret = $secretsService->save($row);

    return Api::respond($secret);
  } catch(Exception $ex) {
    return Api::error($app, $ex);
  }
});

$app->post('/secrets/{id}', function (Silex\Application $app, $id) {
  try {
    $secretsService = new Secret($app);
    $row = $secretsService->getRow($_POST, UserService::current($app), $id);
    $row = $secretsService->save($row);

    return Api::respond($row);
  } catch(Exception $ex) {
    return Api::error($app, $ex);
  }
});

$app->delete('/secrets/{id}', function (Silex\Application $app, $id) {
  try {
    $secretsService = new Secret($app);
    $res = $secretsService->delete(UserService::current($app), $id);

    return Api::respond($res);
  } catch(Exception $ex) {
    return Api::error($app, $ex);
  }
});

$app->get('/user', function() use($app) {
  return Api::respond(UserService::current($app));
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
    return Api::respond(UserService::register($app, $username, 'ROLE_USER', $password));
  }
  $app->abort(403, "no credentials.");
});

$app->get('/test/secrets/{id}', function (Silex\Application $app, $id) {
    $secretsService = new Secret($app);
    $secret = $secretsService->getForUserOrAdmin(UserService::current($app), $id, true, false);
    return $app['twig']->render('edit-secret.html', array(
        'secret' => $secret
    ));
});

$app->get('/test/secrets', function (Silex\Application $app) {
    return $app['twig']->render('new-secret.html', array(
    ));
});
