<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use UserQuery;

class EmailAvailable extends AbstractRule
{
    public function validate($input)
    {
        return UserQuery::create()->filterByEmail($input)->count() === 0;
    }
}