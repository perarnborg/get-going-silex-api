<?php
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

$app->get('/login', function(Request $request) use ($app) {
    return $app['twig']->render('login.html', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
});

$app->get('/user', function() use($app) {
  return json_encode(UserService::current($app));
});

$app->get('/user/register', function() use($app) {
  $username = isset($_GET['username']) ? $_GET['username'] : null;
  $password = isset($_GET['password']) ? $_GET['password'] : null;
  if($username && $password) {
    return json_encode(UserService::register($app, $username, 'ROLE_USER', $password));
  }
  throw new Exception('User not created');
});
