<?php

use App\Middleware\AuthMiddleware;

$app->group('/admin', function () use ($container) {

    $this->get('', 'AdminController:getDashboard')->setName('admin.dashboard');

    $this->group('/users', function () {

        $this->get('', 'AdminController:getUserList')->setName('admin.users');

        $this->get('/new', 'AdminController:getNewUser')->setName('admin.users.new');
        $this->post('/new', 'AdminController:postNewUser');
    });
})->add(new AuthMiddleware($container));