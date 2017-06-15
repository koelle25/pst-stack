<?php

namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Old Input Middleware
 *
 * The class keeps input data from forms etc. between requests so the user doesn't have to enter
 * all information again and again.
 *
 * Just attach it to the Slim App and you're ready to use {{ old.<inputName> }} in the Twig Views.
 *
 * Class OldInputMiddleware
 * @package App\Middleware
 */
class OldInputMiddleware extends Middleware
{
    function __invoke(Request $request, Response $response, callable $next)
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
                if (!isset($_SESSION['old'][$key]) || $_SESSION['old'][$key] !== $value) {
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