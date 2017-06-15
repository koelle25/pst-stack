<?php

namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Authenticated Middleware
 *
 * The class checks whether the user should have access to the current request target.
 * It does this by checking the current authentication status and redirecting to the sign in
 * page if the user is not authenticated.
 *
 * It can be attached to any route or route group you would like to only have access from authenticated users.
 *
 * Class AuthMiddleware
 * @package App\Middleware
 */
class AuthMiddleware extends Middleware
{
    public function __invoke(Request $request, Response $response, callable $next)
    {
        // Check if the user is signed in, redirect to sign in page if that's not the case
        if (!$this->auth->check()) {
            $this->flash->addMessage('error', 'You must be signed in to access that page.');
            return $response->withRedirect($this->router->pathFor('auth.signin'));
        }

        // Next Middleware
        $response = $next($request, $response);
        return $response;
    }
}