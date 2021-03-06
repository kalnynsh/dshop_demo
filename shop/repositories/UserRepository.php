<?php

namespace shop\repositories;

use shop\entities\User\User;

class UserRepository
{
    private $query;

    public function __construct()
    {
        $this->query = User::find();
    }

    public function findByUsernameOrEmail($value): ?User
    {
        return $this->query->andWhere(['or', ['username' => $value], ['email' => $value]])->one();
    }

    public function findByNetworkIdentity($network, $identity): ?User
    {
        return $this->query
            ->joinWith('networks n')
            ->andWhere(['n.network' => $network, 'n.identity' => $identity])
            ->one();
    }

    public function get($id): User
    {
        return $this->getBy(['id' => $id]);
    }

    public function getByEmailConfirmToken($token): User
    {
        return $this->getBy(['email_confirm_token' => $token]);
    }

    public function getByEmail($email): User
    {
        return $this->getBy(['email' => $email]);
    }

    public function getByPasswordResetToken($token): User
    {
        return $this->getBy(['password_reset_token' => $token]);
    }

    public function existsByPasswordResetToken(string $token): bool
    {
        return (bool) User::findByPasswordResetToken($token);
    }

    private function getBy(array $condition): User
    {
        if (!$user = $this->query->andWhere($condition)->one()) {
            throw new NotFoundException('User not found.');
        }

        return $user;
    }

    public function save(User $user): void
    {
        if (!$user->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    public function remove(User $user): void
    {
        if (!$user->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }

    public function findOne($id): ?User
    {
        return $this->findBy(['id' => $id]);
    }

    public function findActiveById($id)
    {
        return $this->findBy([
            'id' => $id,
            'status' => User::STATUS_ACTIVE,
        ]);
    }

    public function findActiveByUsername($username): ?User
    {
        return $this->findBy([
            'username' => $username,
            'status' => User::STATUS_ACTIVE,
        ]);
    }

    private function findBy(array $condition): ?User
    {
        return $this->query->andWhere($condition)->limit(1)->one();
    }
}
