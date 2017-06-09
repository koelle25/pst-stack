<?php

namespace App\Handlers;

use Exception;
use Slim\Http\Request;
use Slim\Http\Response;
use Monolog\Logger;

final class Error extends \Slim\Handlers\Error
{
    protected $logger;

    public function __construct(Logger $logger)
    {
        parent::__construct();
        $this->logger = $logger;
    }

    public function __invoke(Request $request, Response $response, Exception $exception)
    {
        // Log the message
        $this->logger->critical($exception->getMessage());

        return parent::__invoke($request, $response, $exception);
    }
}