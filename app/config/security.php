<?php
$app->register(new Silex\Provider\SessionServiceProvider());

$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'login' => array(
            'pattern' => '^/login$',
        ),
        'user' => array(
            'pattern' => '^/user$',
        ),
        'register' => array(
            'pattern' => '^/user/register$',
        ),
        'secured' => array(
            'pattern' => '^.*$',
//            'http' => true,
            'form' => array('login_path' => '/login', 'check_path' => '/user/signin'),
            'users' => $app->share(function () use ($app) {
                return new UserProvider($app);
            }),
//            'users' => array(
//                'admin' => array('ROLE_ADMIN', 'k7U/snDnxeT/uQu9DfEzNkleVNTiL2Pze2Dmx1WmEfZbfifCdCQH++H2wB0SP4/d+VceMrH777qor3HyOFz7sg=='),
//            ),
        ),
    ),
));
