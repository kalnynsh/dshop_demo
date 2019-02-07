<?php

namespace common\auth;

use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use shop\repositories\UserRepository;
use shop\entities\User\User;

class Identity implements IdentityInterface
{
    /** @property $user shop\entities\User\User */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public static function findIdentity($id): ?self
    {
        /** @var $user shop\entities\User\User */
        $user = self::getRepository()->findActiveById($id);

        return $user ? new self($user) : null;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('`findIdentityByAccessToken` ' . 'isn`t implemented.');
    }

    public function getId(): int
    {
        return $this->user->id;
    }

    public function getAuthKey(): string
    {
        return $this->user->auth_key;
    }

    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    private static function getRepository(): UserRepository
    {
        return \Yii::$container->get(UserRepository::class);
    }
}
