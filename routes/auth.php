<?php

use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

$app->group('/auth', function () use ($container) {

    $this->group('', function () {

        $this->get('/signup', 'AuthController:getSignUp')->setName('auth.signup');
        $this->post('/signup', 'AuthController:postSignUp');

        $this->get('/signin', 'AuthController:getSignIn')->setName('auth.signin');
        $this->post('/signin', 'AuthController:postSignIn');
    })->add(new GuestMiddleware($container));

    $this->group('', function () {

        $this->get('/password/change', 'PasswordController:getChangePassword')->setName('auth.password.change');
        $this->post('/password/change', 'PasswordController:postChangePassword');

        $this->get('/signout', 'AuthController:getSignOut')->setName('auth.signout');
    })->add(new AuthMiddleware($container));
});