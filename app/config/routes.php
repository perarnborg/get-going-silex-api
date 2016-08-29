<?php
use Symfony\Component\HttpFoundation\Request;

$app->get('/', function() use($app) {
  return $app->redirect('/colors');
});

$app->get('/colors', function() use($app) {
  return ColorController::index($app);
});

$app->get('/colors/{id}', function (Silex\Application $app, $id) {
  return ColorController::get($app, $id);
});

$app->get('/secrets/user', function (Silex\Application $app) {
  return SecretController::indexForUser($app);
});

$app->get('/secrets/user/{id}', function (Silex\Application $app, $id) {
  return SecretController::getForUser($app, $id);
});

$app->post('/secrets', function (Silex\Application $app) {
  return SecretController::create($app, $_POST);
});

$app->post('/secrets/{id}', function (Silex\Application $app, $id) {
  return SecretController::update($app, $id, $_POST);
});

$app->delete('/secrets/{id}', function (Silex\Application $app, $id) {
  return SecretController::delete($app, $id);
});

$app->get('/user', function() use($app) {
  return UserController::current($app);
});

$app->get('/user/login', function(Request $request) use ($app) {
  return UserController::login($app);
});

$app->post('/user/register', function() use($app) {
  return UserController::register($app, $post);
});


// TEST ROUTES
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
