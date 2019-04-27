<?php

namespace shop\services\auth;

use shop\services\manage\access\RoleManagerService;
use shop\services\manage\access\Rbac;
use shop\services\auth\events\UserSignUpRequested;
use shop\services\auth\events\UserSignUpConfirmed;
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
 * @property IEventDispatcher $dispatcher
 */
class SignupService
{
    private $users;
    private $roles;
    private $transaction;
    private $yiiApp;
    private $dispatcher;

    public function __construct(
        UserRepository $users,
        RoleManagerService $roles,
        TransactionManager $transaction,
        IEventDispatcher $dispatcher
    ) {
        $this->users = $users;
        $this->roles = $roles;
        $this->transaction = $transaction;
        $this->yiiApp = \Yii::$app;
        $this->dispatcher = $dispatcher;
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

        $this->dispatcher->dispatch(new UserSignUpRequested($user));
    }

    public function confirm($token): void
    {
        if (empty($token)) {
            throw new \DomainException('Empty confirm token.');
        }

        $user = $this->users->getByEmailConfirmToken($token);
        $user->confirmSignup();
        $this->users->save($user);
        $this->dispatcher->dispatch(new UserSignUpConfirmed($user));
    }
}
