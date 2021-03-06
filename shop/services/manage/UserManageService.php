<?php

namespace shop\services\manage;

use shop\services\newsletter\MailNewsletter;
use shop\services\manage\access\RoleManagerService;
use shop\services\manage\access\Rbac;
use shop\services\TransactionManager;
use shop\repositories\UserRepository;
use shop\forms\manage\User\UserEditForm;
use shop\forms\manage\User\UserCreateForm;
use shop\entities\User\User;

/**
 * Managing users
 *
 * @property UserRepository $repository
 * @property RoleManagerService $roles
 * @property TransactionManager $transaction
 */
class UserManageService
{
    private $repository;
    private $roles;
    private $transaction;
    private $newsletter;

    public function __construct(
        UserRepository $repository,
        RoleManagerService $roles,
        TransactionManager $transaction,
        MailNewsletter $newsletter
    ) {
        $this->repository = $repository;
        $this->roles = $roles;
        $this->transaction = $transaction;
        $this->newsletter = $newsletter;
    }

    public function create(UserCreateForm $form): User
    {
        $user = User::create(
            $form->username,
            $form->email,
            $form->phone,
            $form->password
        );

        $this->transaction->wrap(function () use ($user) {
            $this->repository->save($user);
            $this->roles->assign($user->id, Rbac::ROLE_USER);
            $this->newsletter->subscribe($user->email);
        });

        return $user;
    }

    public function edit($id, UserEditForm $form): void
    {
        $user = $this->repository->get($id);

        $user->edit(
            $form->username,
            $form->email,
            $form->phone
        );

        $this->transaction->wrap(function () use ($user, $form) {
            $this->repository->save($user);
            $this->roles->assign($user->id, $form->role);
        });
    }

    public function assignRole($userId, $role): void
    {
        $user = $this->repository->get($userId);
        $this->roles->assign($user->id, $role);
    }

    public function remove($id): void
    {
        $user = $this->repository->get($id);
        $this->newsletter->unsubscribe($user->email);
        $this->repository->remove($user);
    }

    public function findOne($userId): ?User
    {
        return $this->repository->findOne($userId);
    }

    public function findByUsernameOrEmail($username): ?User
    {
        return $this->repository->findByUsernameOrEmail($username);
    }
}
