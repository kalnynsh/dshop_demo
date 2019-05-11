<?php

namespace shop\entities\User\events;

use shop\entities\User\User;

class UserSignupConfirmed
{
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
