<?php

namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;

class CsrfViewMiddleware extends Middleware
{
    function __invoke(Request $request, Response $response, $next)
    {
        $this->view->getEnvironment()->addGlobal('csrf', [
            'field' => '
                <input type="hidden" name="'.$this->csrf->getTokenNameKey().'" value="'.$this->csrf->getTokenName().'">
                <input type="hidden" name="'.$this->csrf->getTokenValueKey().'" value="'.$this->csrf->getTokenValue().'">
            '
        ]);

        $response = $next($request, $response);
        return $response;
    }
}