<?php

namespace shop\services\manage;

use shop\services\manage\access\Rbac;
use shop\services\manage\access\RoleManagerService;
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

    public function __construct(
        UserRepository $repository,
        RoleManagerService $roles,
        TransactionManager $transaction
    ) {
        $this->repository = $repository;
        $this->roles = $roles;
        $this->transaction = $transaction;
    }

    public function create(UserCreateForm $form): User
    {
        $user = User::create(
            $form->username,
            $form->email,
            $form->password
        );

        $this->transaction->wrap(function () use ($user) {
            $this->repository->save($user);
            $this->roles->assing($user->id, Rbac::ROLE_USER);
        });

        return $user;
    }

    public function edit($id, UserEditForm $form): void
    {
        $user = $this->repository->get($id);

        $user->edit(
            $form->username,
            $form->email
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
