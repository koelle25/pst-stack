<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use Respect\Validation\Validator as v;
use Slim\Http\Request;
use Slim\Http\Response;

class PasswordController extends Controller
{
    public function getChangePassword(Request $request, Response $response)
    {
        return $this->view->render($response, 'auth/password/change.twig');
    }

    public function postChangePassword(Request $request, Response $response)
    {
        $validation = $this->validator->validate($request, [
            'currentPassword' => v::noWhitespace()->notEmpty()->matchesPassword($this->auth->user()->getPassword()),
            'password' => v::noWhitespace()->notEmpty()
        ]);

        if ($validation->failed()) {
            $this->flash->addMessage('error', 'Please check your input.');

            return $response->withRedirect($this->router->pathFor('auth.password.change'));
        }

        $password = filter_var($request->getParam('password'), FILTER_SANITIZE_STRING);

        $this->auth->user()->updatePassword($password);

        $this->flash->addMessage('success', 'Your password has been changed.');
        return $response->withRedirect($this->router->pathFor('home'));
    }
}