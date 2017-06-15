<?php

namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;

class OldInputMiddleware extends Middleware
{
    function __invoke(Request $request, Response $response, $next)
    {
        // Navigated to another page, empty the $_SESSION['old'] array
        if (isset($_SESSION['old_path']) && $_SESSION['old_path'] !== $request->getRequestTarget()) {
            $_SESSION['old'] = [];
        }

        // Fill a global view variable with the $_SESSION['old'] contents (if any)
        if (isset($_SESSION['old'])) {
            $this->view->getEnvironment()->addGlobal('old', $_SESSION['old']);
        }

        // Update the $_SESSION['old'] with the current request parameters
        if (!isset($_SESSION['old'])) {
            $_SESSION['old'] = $request->getParams();
        } else {
            $requestParams = $request->getParams();
            foreach ($requestParams as $key => $value) {
                if (isset($_SESSION['old'][$key]) || $_SESSION['old'][$key] !== $value) {
                    $_SESSION['old'][$key] = $value;
                }
            }
        }

        // Save $_SESSION['old_path'] to the current request target
        $_SESSION['old_path'] = $request->getRequestTarget();

        // Next Middleware
        $response = $next($request, $response);
        return $response;
    }
}