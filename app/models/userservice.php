<?php
use Symfony\Component\Security\Core\User\User;

class UserService
{
    private static function db($app)
    {
        return new Db($app, 'users');
    }

    public static function current($app)
    {
      $user = null;
      $token = $app['security.token_storage']->getToken();
      if (null !== $token) {
        $u = $token->getUser();
        if($u->isEnabled()) {
            $user = new stdClass();
            $user->username = $u->getUsername();
            $user->roles = $u->getRoles();
        }
      }
      return $user;
    }

    public static function register($app, $username, $roles, $password)
    {
        $db = self::db($app);
        $user = new User($username, null, explode(',', $roles), true, true, true, true);
        $encoder = $app['security.encoder_factory']->getEncoder($user);
        $passpword_encoded = $encoder->encodePassword($password, $user->getSalt());
        $db->save(array('username' => $username, 'roles' => $roles, 'password' => $passpword_encoded));
    }
}
