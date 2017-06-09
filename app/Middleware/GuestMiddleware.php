<?php

namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;

class GuestMiddleware extends Middleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        if ($this->auth->check()) {
            $this->flash->addMessage('warning', 'You can\'t access this page while you\'re signed in.');
            return $response->withRedirect($this->router->pathFor('home'));
        }

        $response = $next($request, $response);
        return $response;
    }
}