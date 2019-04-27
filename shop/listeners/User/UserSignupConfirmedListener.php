<?php

namespace shop\listeners\User;

use shop\services\newsletter\MailNewsletter;
use shop\services\auth\events\UserSignUpConfirmed;

class UserSignupConfirmedListener
{
    private $newsletter;

    public function __construct(MailNewsletter $newsletter)
    {
        $this->newsletter = $newsletter;
    }

    public function handle(UserSignUpConfirmed $event): void
    {
        $this->newsletter->subscribe($event->user->email);
    }
}
