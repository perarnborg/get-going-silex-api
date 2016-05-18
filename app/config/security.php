<?php
$app->register(new Silex\Provider\SessionServiceProvider());

$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
//        'user' => array(
//            'pattern' => '^/user$',
//        ),
        'login' => array(
            'pattern' => '^/user/login$',
        ),
        'register' => array(
            'pattern' => '^/user/register$',
        ),
        'secured' => array(
            'pattern' => '^.*$',
            'form' => array('login_path' => '/user/login', 'check_path' => '/user/authenticate'),
            'users' => $app->share(function () use ($app) {
                return new UserProvider($app);
            }),
//            'users' => array(
//                'admin' => array('ROLE_ADMIN', 'k7U/snDnxeT/uQu9DfEzNkleVNTiL2Pze2Dmx1WmEfZbfifCdCQH++H2wB0SP4/d+VceMrH777qor3HyOFz7sg=='),
//            ),
        ),
    ),
));
