<?php

use App\Middleware\AuthMiddleware;

$app->group('/admin', function () use ($container) {

    $this->get('', 'AdminController:getDashboard')->setName('admin.dashboard');
})->add(new AuthMiddleware($container));