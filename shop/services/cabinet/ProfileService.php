<?php

namespace shop\services\cabinet;

use shop\repositories\UserRepository;
use shop\forms\manage\UserProfile\PrifileEditForm;
use shop\entities\User\User;

class ProfileService
{
    private $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function edit($id, PrifileEditForm $form): void
    {
        $user = $this->users->get($id);
        $user->editProfile($form->email, $form->phone);
        $this->users->save($user);
    }

    public function get($id): User
    {
        return $this->users->get($id);
    }

    public function find($id): ?User
    {
        return $this->users->findOne($id);
    }
}
