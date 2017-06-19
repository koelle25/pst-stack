<?php

namespace App\Models;

use App\Models\Base\User as BaseUser;
use RandomLib\Factory;

/**
 * Skeleton subclass for representing a row from the 'user' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class User extends BaseUser
{
    public function updatePassword($newPassword)
    {
        $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        parent::setPassword($newPassword);

        return parent::save();
    }

    public static function createPassword($length = 16)
    {
        $factory = new Factory();
        $generator = $factory->getMediumStrengthGenerator();
        $alphabet = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ+/.,-_?!';
        return $generator->generateString($length, $alphabet);
    }
}
