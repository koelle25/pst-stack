<?php

namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;

class AuthMiddleware extends Middleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        if (!$this->auth->check()) {
            $this->flash->addMessage('error', 'You must be signed in to access that page.');
            return $response->withRedirect($this->router->pathFor('auth.signin'));
        }

        $response = $next($request, $response);
        return $response;
    }
}