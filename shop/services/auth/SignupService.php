<?php

namespace shop\services\auth;

use shop\services\manage\access\RoleManagerService;
use shop\services\manage\access\Rbac;
use shop\services\TransactionManager;
use shop\repositories\UserRepository;
use shop\forms\auth\SignupForm;
use shop\entities\User\User;
use shop\dispatchers\IEventDispatcher;

/**
 * Signup new user
 *
 * @property UserRepository $users
 * @property RoleManagerService $roles
 * @property TransactionManager $transaction
 * @property Component $yiiApp
 */
class SignupService
{
    private $users;
    private $roles;
    private $transaction;
    private $yiiApp;

    public function __construct(
        UserRepository $users,
        RoleManagerService $roles,
        TransactionManager $transaction
    ) {
        $this->users = $users;
        $this->roles = $roles;
        $this->transaction = $transaction;
        $this->yiiApp = \Yii::$app;
    }

    public function signup(SignupForm $form): void
    {
        $user = User::requestSignup(
            $form->username,
            $form->email,
            $form->phone,
            $form->password
        );

        $this->transaction->wrap(function () use ($user) {
            $this->users->save($user);
            $this->roles->assign($user->id, Rbac::ROLE_USER);
        });
    }

    public function confirm($token): void
    {
        if (empty($token)) {
            throw new \DomainException('Empty confirm token.');
        }

        $user = $this->users->getByEmailConfirmToken($token);
        $user->confirmSignup();
        $this->users->save($user);
    }
}
