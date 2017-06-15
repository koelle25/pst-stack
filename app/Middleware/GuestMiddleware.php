<?php

namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Guest Middleware
 *
 * The class checks whether the user should have access to the current request target.
 * It does this by checking the current authentication status and redirecting to the home
 * page if the user is signed in already.
 *
 * It can be attached to any route or route group you would like to only have access from guest users.
 *
 * Class GuestMiddleware
 * @package App\Middleware
 */
class GuestMiddleware extends Middleware
{
    public function __invoke(Request $request, Response $response, callable $next)
    {
        // Check if the user is already signed in, redirect to home page if that's the case
        if ($this->auth->check()) {
            $this->flash->addMessage('warning', 'You can\'t access this page while you\'re signed in.');
            return $response->withRedirect($this->router->pathFor('home'));
        }

        // Next Middleware
        $response = $next($request, $response);
        return $response;
    }
}