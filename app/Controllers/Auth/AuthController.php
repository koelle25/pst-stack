<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Models\User;
use App\UUID;
use Respect\Validation\Validator as v;
use Slim\Http\Request;
use Slim\Http\Response;

class AuthController extends Controller
{
    public function getSignUp(Request $request, Response $response)
    {
        return $this->view->render($response, 'auth/signup.twig');
    }

    public function postSignUp(Request $request, Response $response)
    {
        $validation = $this->validator->validate($request, [
            'email' => v::noWhitespace()->notEmpty()->email()->emailAvailable(),
            'firstName' => v::notEmpty()->alpha('äöüß'),
            'lastName' => v::notEmpty()->alpha('äöüß'),
            'password' => v::noWhitespace()->notEmpty()
        ]);

        if ($validation->failed()) {
            $this->flash->addMessage('error', 'Please check your input.');
            return $response->withRedirect($this->router->pathFor('auth.signup'));
        }

        $emailAddress = strtolower(filter_var($request->getParam('email'), FILTER_SANITIZE_STRING));
        $password = filter_var($request->getParam('password'), FILTER_SANITIZE_STRING);
        $firstName = filter_var($request->getParam('firstName'), FILTER_SANITIZE_STRING);
        $lastName = filter_var($request->getParam('lastName'), FILTER_SANITIZE_STRING);

        $user = new User();
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setEmail($emailAddress);
        $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
        $user->setUUID(UUID::v4());

        if ($user->save()) {
            $this->flash->addMessage('success', 'Signup successful. You are now signed in!');

            $this->auth->attempt($user->getEmail(), $password);

            return $response->withRedirect($this->router->pathFor('home'));
        }

        $this->flash->addMessage('error', 'Sorry, something went wrong');
        return $response->withRedirect($this->router->pathFor('auth.signup'));
    }

    public function getSignIn(Request $request, Response $response)
    {
        return $this->view->render($response, 'auth/signin.twig');
    }

    public function postSignIn(Request $request, Response $response)
    {
        $validation = $this->validator->validate($request, [
            'email' => v::noWhitespace()->notEmpty()->email(),
            'password' => v::noWhitespace()->notEmpty()
        ]);

        if ($validation->failed()) {
            $this->flash->addMessage('error', 'Please check your input.');
            return $response->withRedirect($this->router->pathFor('auth.signin'));
        }

        $emailAddress = strtolower(filter_var($request->getParam('email'), FILTER_SANITIZE_STRING));
        $password = filter_var($request->getParam('password'), FILTER_SANITIZE_STRING);

        $auth = $this->auth->attempt($emailAddress, $password);

        if (!$auth) {
            $this->flash->addMessage('error', 'Invalid email address and/or password');
            return $response->withRedirect($this->router->pathFor('auth.signin'));
        }

        $this->flash->addMessage('success', 'Successfully signed in');
        return $response->withRedirect($this->router->pathFor('home'));
    }

    public function getSignOut(Request $request, Response $response)
    {
        $this->auth->logout();

        return $response->withRedirect($this->router->pathFor('home'));
    }

    public function getProfile(Request $request, Response $response)
    {
        return $this->view->render($response, 'auth/profile.twig', [
            'user' => $this->auth->user()
        ]);
    }
}