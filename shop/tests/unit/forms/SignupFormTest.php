<?php

namespace shop\tests\unit\forms;

use common\fixtures\UserFixture;
use shop\forms\auth\SignupForm;

class SignupFormTest extends \Codeception\Test\Unit
{
    /**
     * @var \shop\tests\UnitTester
     */
    protected $tester;

    public function _before()
    {
        $this->tester->haveFixtures([
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php'
            ]
        ]);
    }

    public function testCorrectSignup()
    {
        $form = new SignupForm([
            'username' => 'some_username',
            'email' => 'some_email@example.com',
            'phone' => '792665422291',
            'password' => 'some_password',
        ]);
        expect_that($form->validate());
    }

    public function testNotCorrectSignup()
    {
        $form = new SignupForm([
            'username' => 'alex.runner',
            'email' => 'golem.beired@hotmail.com',
            'phone' => 'abc0000000',
            'password' => 'some_password',
        ]);

        expect_not($form->validate());
        expect_that($form->getErrors('username'));
        expect_that($form->getErrors('email'));
        expect_that($form->getErrors('phone'));

        expect($form->getFirstError('username'))
            ->equals('This username has already been taken.');

        expect($form->getFirstError('email'))
            ->equals('This email address has already been taken.');
    }
}
