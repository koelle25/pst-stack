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

    public function newUser(Request $request, Response $response)
    {
        $validation = $this->validator->validate($request, [
            'firstName' => v::notEmpty()->alpha('äöüß'),
            'lastName' => v::notEmpty()->alpha('äöüß'),
            'email' => v::noWhitespace()->notEmpty()->email()->emailAvailable(),
        ]);

        if ($validation->failed()) {
            $this->flash->addMessage('error', '<strong>Validation error(s)!</strong> Please check your input below.');
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
            $this->flash->addMessage('success',
                '<strong>Done!</strong>' .
                ' User <em>'.$user->getEmail().'</em> created successfully.' .
                '<div class="input-group col-md-6 col-md-offset-3">' .
                '<span class="input-group-addon">The password is:</span>' .
                '<input type="text" class="form-control" readonly value="'.$password.'">' .
                '</div>'
            );

            if ($request->getParam('commit') !== null) {
                return $response->withRedirect($this->router->pathFor('admin.users'));
            }

            if ($request->getParam('continue') !== null) {
                $_SESSION['forget_old_input'] = true;
                return $response->withRedirect($this->router->pathFor('admin.users.new'));
            }
        }

        $this->flash->addMessage('error', '<strong>Error!</strong> Sorry, something went wrong');
        return $response->withRedirect($this->router->pathFor('admin.users.new'));
    }

    public function getEditUser(Request $request, Response $response, $args)
    {
        $user = UserQuery::create()->findOneById($args['id']);

        return $this->container->view->render($response, 'admin/users/edit.twig', [
            'isAdministration' => true,
            'isUserAdministration' => true,
            'user' => $user
        ]);
    }

    public function editUser(Request $request, Response $response, $args)
    {
        $user = UserQuery::create()->findOneById($args['id']);

        $validationRules = [
            'firstName' => v::notEmpty()->alpha('äöüß'),
            'lastName' => v::notEmpty()->alpha('äöüß')
        ];

        $emailAddress = strtolower(filter_var($request->getParam('email'), FILTER_SANITIZE_STRING));
        if ($user->getEmail() != $emailAddress) {
            $validationRules['email'] = v::noWhitespace()->notEmpty()->email()->emailAvailable();
        }

        $validation = $this->validator->validate($request, $validationRules);

        if ($validation->failed()) {
            $this->flash->addMessage('error', '<strong>Validation error(s)!</strong> Please check your input below.');
            return $response->withRedirect($this->router->pathFor('admin.users.edit', $args));
        }

        $firstName = filter_var($request->getParam('firstName'), FILTER_SANITIZE_STRING);
        $lastName = filter_var($request->getParam('lastName'), FILTER_SANITIZE_STRING);

        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setEmail($emailAddress);

        if (!$user->isModified()) {
            $this->flash->addMessage('info', 'No attributes have been changed.');

            return $response->withRedirect($this->router->pathFor('admin.users'));
        }

        if ($user->save()) {
            $this->flash->addMessage('success', '<strong>Done!</strong> Editing user was successful.');

            return $response->withRedirect($this->router->pathFor('admin.users'));
        }

        $this->flash->addMessage('error', '<strong>Error!</strong> Sorry, something went wrong');
        return $response->withRedirect($this->router->pathFor('admin.users.edit', $args));
    }

    public function resetPassword(Request $request, Response $response, $args)
    {
        $user = UserQuery::create()->findOneById($args['id']);

        $newPassword = User::createPassword();

        if ($user->updatePassword($newPassword)) {
            $this->flash->addMessage('success',
                '<strong>Done!</strong>' .
                ' Password for <em>'.$user->getEmail().'</em> reset.' .
                '<div class="input-group col-md-6 col-md-offset-3">' .
                    '<span class="input-group-addon">New password:</span>' .
                    '<input type="text" class="form-control" readonly value="'.$newPassword.'">' .
                '</div>'
            );

            return $response->withRedirect($this->router->pathFor('admin.users'));
        }

        $this->flash->addMessage('error', '<strong>Error!</strong> Sorry, something went wrong');
        return $response->withRedirect($this->router->pathFor('admin.users', $args));
    }

    public function getDeleteUser(Request $request, Response $response, $args)
    {
        $user = UserQuery::create()->findOneById($args['id']);

        if ($_SESSION['user'] == $user->getUUID()) {
            $this->flash->addMessage('error', '<strong>Error!</strong> Cannot delete currently signed in user.');

            return $response->withRedirect($this->router->pathFor('admin.users'));
        }

        return $this->container->view->render($response, 'admin/users/delete.twig', [
            'isAdministration' => true,
            'isUserAdministration' => true,
            'user' => $user
        ]);
    }

    public function deleteUser(Request $request, Response $response, $args)
    {
        if ($request->getParam('commit') !== null) {
            $user = UserQuery::create()->findOneById($args['id']);

            if ($_SESSION['user'] == $user->getUUID()) {
                $this->flash->addMessage('error', '<strong>Error!</strong> Cannot delete currently signed in user.');

                return $response->withRedirect($this->router->pathFor('admin.users'));
            }

            $user->delete();

            $this->flash->addMessage('success',
                '<strong>Done!</strong>' .
                ' User <em>'.$user->getEmail().'</em> deleted successfully.'
            );

             return $response->withRedirect($this->router->pathFor('admin.users'));
        }

        if ($request->getParam('cancel') !== null) {
            return $response->withRedirect($this->router->pathFor('admin.users'));
        }

        $this->flash->addMessage('error', '<strong>Error!</strong> Sorry, something went wrong');
        return $response->withRedirect($this->router->pathFor('admin.users'));
    }
}