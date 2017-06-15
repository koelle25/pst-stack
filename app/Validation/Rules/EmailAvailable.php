<?php

namespace App\Validation\Rules;

use App\Models\UserQuery;
use Respect\Validation\Rules\AbstractRule;

class EmailAvailable extends AbstractRule
{
    public function validate($input)
    {
        return UserQuery::create()->filterByEmail($input)->count() === 0;
    }
}