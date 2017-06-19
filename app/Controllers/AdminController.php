<?php

namespace App\Controllers;

use App\Models\UserQuery;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class AdminController extends Controller
{
    public function getDashboard(Request $request, Response $response)
    {
        return $this->container->view->render($response, 'admin/dashboard.twig');
    }

    public function getUserList(Request $request, Response $response)
    {
        $users = UserQuery::create()->find();

        return $this->container->view->render($response, 'admin/users/list.twig', [
            'users' => $users
        ]);
    }
}