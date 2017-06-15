<?php

namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Cross-Site-Request-Forgery View Middleware
 *
 * The class adds a global view variable with the needed csrf tokens for form validation and csrf protection.
 *
 * Just attach it to the Slim App and you're ready to use {{ csrf.field | raw }} in the Twig Views.
 *
 * Class CsrfViewMiddleware
 * @package App\Middleware
 */
class CsrfViewMiddleware extends Middleware
{
    function __invoke(Request $request, Response $response, callable $next)
    {
        // Fill a global view variable with the csrf tokens
        $this->view->getEnvironment()->addGlobal('csrf', [
            'field' => '
                <input type="hidden" name="'.$this->csrf->getTokenNameKey().'" value="'.$this->csrf->getTokenName().'">
                <input type="hidden" name="'.$this->csrf->getTokenValueKey().'" value="'.$this->csrf->getTokenValue().'">
            '
        ]);

        // Next Middleware
        $response = $next($request, $response);
        return $response;
    }
}