<?php

namespace shop\tests\unit\forms;

use shop\forms\auth\LoginForm;
use common\fixtures\UserFixture;

/**
 * Login form test
 */
class LoginFormTest extends \Codeception\Test\Unit
{
    /**
     * @var \shop\tests\UnitTester
     */
    protected $tester;

    public function _before()
    {
        $this->tester->haveFixtures([
            'user' => [
                'class' => UserFixture::className(),
                'dataFile' => codecept_data_dir() . 'user.php'
            ]
        ]);
    }

    public function testBlank()
    {
        $model = new LoginForm([
            'username' => '',
            'password' => '',
        ]);
        expect_not($model->validate());
    }

    public function testCorrect()
    {
        $model = new LoginForm([
            'username' => 'vudi.ailend',
            'password' => 'secret_words',
        ]);
        expect_that($model->validate());
    }
}
