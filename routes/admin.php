<?php

use App\Middleware\AuthMiddleware;

$app->group('/admin', function () use ($container) {

    $this->get('', 'AdminController:getDashboard')->setName('admin.dashboard');

    $this->group('/users', function () {

        $this->get('', 'AdminController:getUserList')->setName('admin.users');

        $this->get('/new', 'AdminController:getNewUser')->setName('admin.users.new');
        $this->post('/new', 'AdminController:postNewUser');

        $this->group('/{id}', function () {

            $this->get('/edit', 'AdminController:getEditUser')->setName('admin.users.edit');
            $this->patch('/edit', 'AdminController:patchEditUser');
        });
    });
})->add(new AuthMiddleware($container));