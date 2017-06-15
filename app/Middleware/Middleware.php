<?php

namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Abstract Middleware
 *
 * This class is the base for all custom middleware. It saves the app-container and adds
 * a magic get method for it for convenient access to other container items.
 *
 * Just extend your custom middleware from this and you're ready to go.
 *
 * Class Middleware
 * @package App\Middleware
 */
abstract class Middleware
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public abstract function __invoke(Request $request, Response $response, callable $next);

    public function __get($property)
    {
        if ($this->container->{$property}) {
            return $this->container->{$property};
        }
    }
}