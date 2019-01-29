<?php

namespace shop\tests\unit\entities\User;

use Codeception\Test\Unit;
use shop\entities\User\User;

class RequestSignupTest extends Unit
{
    public function testSuccess()
    {
        $user = User::requestSignup(
            $username = 'Johnny Dough',
            $email = 'johnny-douhg@example.com',
            $password = 'secret'
        );

        $this->assertEquals($username, $user->username);
        $this->assertEquals($email, $user->email);
        $this->assertNotEmpty($user->password_hash);
        $this->assertNotEquals($password, $user->password_hash);
        $this->assertNotEmpty($user->created_at);
        $this->assertNotEmpty($user->auth_key);
        $this->assertNotEmpty($user->email_confirm_token);
        $this->assertFalse($user->isActive());
        $this->assertTrue(($user->isWait()));
    }
}
