<?php

namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;

class ValidationErrorsMiddleware extends Middleware
{
    function __invoke(Request $request, Response $response, $next)
    {
        $this->view->getEnvironment()->addGlobal('errors', $_SESSION['errors']);
        unset($_SESSION['errors']);

        $response = $next($request, $response);
        return $response;
    }
}