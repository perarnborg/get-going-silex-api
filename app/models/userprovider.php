<?php
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class UserProvider implements UserProviderInterface
{
    private $app, $db;

    public function __construct($app)
    {
        $this->app = $app;
        $this->db = new Db($app, 'users');
    }

    public function loadUserByUsername($username)
    {
        $user = $this->db->getWhere(array('username' => $username));
        if (!$user)
        {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }

        return new User($user['username'], $user['password'], explode(',', $user['roles']), true, true, true, true);
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'Symfony\Component\Security\Core\User\User';
    }
}