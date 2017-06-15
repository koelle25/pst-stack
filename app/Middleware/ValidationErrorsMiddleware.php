<?php

namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Validation Errors Middleware
 *
 * The class adds a global view variable with all occurred errors while validating
 * some input. You can then use this variable in your views to show the errors to
 * the user.
 *
 * Just attach it to the Slim App and you're ready to use {{ errors.<inputName> }} in the Twig Views.
 *
 * Class ValidationErrorsMiddleware
 * @package App\Middleware
 */
class ValidationErrorsMiddleware extends Middleware
{
    function __invoke(Request $request, Response $response, callable $next)
    {
        // Fill a global view variable with the $_SESSION['errors'] contents (if any)
        if (isset($_SESSION['errors'])) {
            $this->view->getEnvironment()->addGlobal('errors', $_SESSION['errors']);
            unset($_SESSION['errors']);
        }

        // Next Middleware
        $response = $next($request, $response);
        return $response;
    }
}