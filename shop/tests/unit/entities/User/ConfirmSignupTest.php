<?php

namespace shop\tests\unit\entities\User;

use Codeception\Test\Unit;
use shop\entities\User\User;

class ConfirmSignupTest extends Unit
{
    private function getWaitUser()
    {
        $user = new User([
            'username' => 'Hover Duglas',
            'email' => 'hover-suglas@example.com',
            'status' => User::STATUS_WAIT,
            'email_confirm_token' => 'token',
        ]);

        return $user;
    }

    private function getActiveUser()
    {
        $user = new User([
            'username' => 'Max Dough',
            'email' => 'max-douhg@example.com',
            'status' => User::STATUS_ACTIVE,
            'email_confirm_token' => null,
        ]);

        return $user;
    }

    public function testSuccess()
    {
        $user = $this->getWaitUser();

        $user->confirmSignup();

        $this->assertEmpty($user->email_confirm_token);
        $this->assertFalse($user->isWait());
        $this->assertTrue($user->isActive());
    }

    public function testUserAlreadyActive()
    {
        $user = $this->getActiveUser();

        $this->expectExceptionMessage('User is already active.');

        $user->confirmSignup();
    }
}
