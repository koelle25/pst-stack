<?php

namespace App\Auth;

use App\Models\UserQuery;

class Auth
{
    public function user()
    {
        return UserQuery::create()->findOneByUUID($_SESSION['user']);
    }

    public function check()
    {
        return isset($_SESSION['user']);
    }

    public function attempt($email, $password)
    {
        $user = UserQuery::create()->filterByEmail($email)->findOne();

        if (!$user) {
            return false;
        }

        if (password_verify($password, $user->getPassword())) {
            $_SESSION['user'] = $user->getUUID();

            return true;
        }

        return false;
    }

    public function logout()
    {
        unset($_SESSION['user']);
    }
}