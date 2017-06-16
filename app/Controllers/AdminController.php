<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class AdminController extends Controller
{
    public function getDashboard(Request $request, Response $response)
    {
        return $this->container->view->render($response, 'admin/dashboard.twig');
    }
}