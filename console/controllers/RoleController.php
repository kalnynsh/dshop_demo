<?php

namespace console\controllers;

use yii\console\Controller;
use shop\services\manage\UserManageService;
use shop\helpers\UserHelper;
use shop\entities\User\User;

/**
 * Interactive console RBAC Role manager
 *
 * @property UserManageService $users
 */
class RoleController extends Controller
{
    private $users;
    private $pswd;

    public function __construct(
        $id,
        $module,
        UserManageService $users,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->users = $users;
        $this->pswd = getenv('ROLE_MANAGERS_CODE');
    }

    public function actionAssign(): void
    {
        $username = $this->prompt('Username:', ['required' => true]);
        $user = $this->findUser($username);

        $this->prompt('Enter code:', [
            'required' => true,
            'validator' => function ($input, &$error) {
                if (empty($this->pswd)) {
                    $error = 'Code for Role management not set!' . PHP_EOL;
                    return false;
                }

                if ($input !== $this->pswd) {
                    $error = 'Incorrect!' . PHP_EOL;
                    return false;
                }

                return true;
            }
        ]);

        $role = $this->select('Role:', $this->rolesList());

        $this->users->assignRole($user->id, $role);
        $this->stdout('Done!' . PHP_EOL);
    }

    private function findUser($username): User
    {
        if (!$user = $this->users->findByUsernameOrEmail($username)) {
            throw new \Exception('User isn`t found.');
        }

        return $user;
    }

    private function rolesList()
    {
        return UserHelper::rolesList();
    }
}
