<?php

use Slim\Container;
use Respect\Validation\Validator as v;

#SLIM instantiate
// The App itself
$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => $conf['app.debug']
    ]
]);
$container = $app->getContainer();

// Dependencies
$container['auth'] = function () {
    return new \App\Auth\Auth();
};
$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};
$container['view'] = function (Container $container) use ($conf) {
    $view = new \Slim\Views\Twig(
        $conf['app.template.dir'],
        [
            'cache' => $conf['app.template.cache'],
            'debug' => $conf['app.template.debug'],
            'auto_reload' => $conf['app.template.auto_reload'],
        ]
    );
    $view->addExtension(
        new \App\TwigExtension(
            $container->router,
            $container->request->getUri()
        )
    );
    $view->addExtension(
        new Twig_Extension_Debug()
    );
    $view->addExtension(new Knlv\Slim\Views\TwigMessages(
        $container->flash
    ));
    $view->getEnvironment()->addGlobal('auth', [
        'check' => $container->auth->check(),
        'user' => $container->auth->user()
    ]);

    return $view;
};
$container['logger'] = function () use ($conf) {
    $logger = new \Monolog\Logger('app_logger');
    $filename = $conf['app.tmp_dir'].'app.log';
    $file_handler = new Monolog\Handler\StreamHandler($filename);
    $logger->pushHandler($file_handler);

    return $logger;
};
$container['errorLogger'] = function () use ($conf) {
    $logger = new \Monolog\Logger('error_logger');
    $filename = $conf['app.tmp_dir'].'error.log';
    $stream = new \Monolog\Handler\StreamHandler($filename, \Monolog\Logger::DEBUG);
    $fingersCrossed = new \Monolog\Handler\FingersCrossedHandler($stream, \Monolog\Logger::ERROR);
    $logger->pushHandler($fingersCrossed);

    return $logger;
};
if (!$conf['app.debug']) {
    $container['errorHandler'] = function (Container $container) {
        return new \App\Handlers\Error($container->errorLogger);
    };
}
$container['csrf'] = function () {
    return new \Slim\Csrf\Guard();
};
$container['validator'] = function () {
    return new \App\Validation\Validator();
};

// Controllers
$container['HomeController'] = function (Container $container) {
    return new \App\Controllers\HomeController($container);
};
$container['AuthController'] = function (Container $container) {
    return new \App\Controllers\Auth\AuthController($container);
};
$container['PasswordController'] = function (Container $container) {
    return new \App\Controllers\Auth\PasswordController($container);
};
$container['AdminController'] = function (Container $container) {
    return new \App\Controllers\AdminController($container);
};

// Middleware
$app->add(new \App\Middleware\ValidationErrorsMiddleware($container));
$app->add(new \App\Middleware\OldInputMiddleware($container));
$app->add(new \App\Middleware\CsrfViewMiddleware($container));
$app->add((new \Psr7Middlewares\Middleware\TrailingSlash(false))->redirect(301)); // 'false' removes trailing slashes, 'true' adds them

// Last Middleware
$app->add($container->csrf);

// Custom Validation Rules
v::with('App\\Validation\\Rules');
#End of SLIM Instance