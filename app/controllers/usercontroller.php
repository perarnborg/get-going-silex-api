<?php
class UserController {
  public static function current($app) {
    return Api::respond(UserService::current($app));
  }

  public static function login($app, $id) {
    return $app['twig']->render('login.html', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
  }

  public static function register($app, $post) {
    $username = isset($post['username']) ? $post['username'] : null;
    $password = isset($post['password']) ? $post['password'] : null;
    if($username && $password) {
      return Api::respond(UserService::register($app, $username, 'ROLE_USER', $password));
    }
    $app->abort(403, "no credentials.");
  }
}
