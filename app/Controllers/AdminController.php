<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\UserQuery;
use App\UUID;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator as v;

class AdminController extends Controller
{
    public function getDashboard(Request $request, Response $response)
    {
        return $this->container->view->render($response, 'admin/dashboard.twig', [
            'isAdministration' => true
        ]);
    }

    public function getUserList(Request $request, Response $response)
    {
        $users = UserQuery::create()->find();

        return $this->container->view->render($response, 'admin/users/list.twig', [
            'isAdministration' => true,
            'users' => $users
        ]);
    }

    public function getNewUser(Request $request, Response $response)
    {
        return $this->container->view->render($response, 'admin/users/new.twig', [
            'isAdministration' => true,
            'isUserAdministration' => true
        ]);
    }

    public function postNewUser(Request $request, Response $response)
    {
        $validation = $this->validator->validate($request, [
            'firstName' => v::notEmpty()->alpha('äöüß'),
            'lastName' => v::notEmpty()->alpha('äöüß'),
            'email' => v::noWhitespace()->notEmpty()->email()->emailAvailable(),
        ]);

        if ($validation->failed()) {
            $this->flash->addMessage('error', 'Please check your input.');
            return $response->withRedirect($this->router->pathFor('admin.users.new'));
        }

        $firstName = filter_var($request->getParam('firstName'), FILTER_SANITIZE_STRING);
        $lastName = filter_var($request->getParam('lastName'), FILTER_SANITIZE_STRING);
        $emailAddress = strtolower(filter_var($request->getParam('email'), FILTER_SANITIZE_STRING));
        $password = USER::createPassword();

        $user = new User();
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setEmail($emailAddress);
        $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
        $user->setUUID(UUID::v4());

        if ($user->save()) {
            $this->flash->addMessage('success', 'User creation successful. The password is: <strong>'.$password.'</strong>');

            if ($request->getParam('commit') !== null) {
                return $response->withRedirect($this->router->pathFor('admin.users'));
            }
            elseif ($request->getParam('continue') !== null) {
                $_SESSION['forget_old_input'] = true;
                return $response->withRedirect($this->router->pathFor('admin.users.new'));
            }
        }

        $this->flash->addMessage('error', 'Sorry, something went wrong');
        return $response->withRedirect($this->router->pathFor('admin.users.new'));
    }
}