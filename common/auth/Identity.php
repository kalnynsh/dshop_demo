<?php

namespace common\auth;

use yii\web\IdentityInterface;
use shop\repositories\UserRepository;
use shop\entities\User\User;
use OAuth2\Storage\UserCredentialsInterface;
use shop\extra\oauth2server\Module;

class Identity implements IdentityInterface, UserCredentialsInterface
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
        $token = self::getOauth()->getServer()->getResourceController()->getToken();

        return !empty($token['user_id'])
                    ? static::findIdentity($token['user_id'])
                    : null;
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

    public function checkUserCredentials($username, $password): bool
    {
        if ($user = $this->findActiveUserByName($username)) {
            return $user->validatePassword($password);
        }

        return false;
    }

    public function getUserDetails($username): array
    {
        $user = $this->findActiveUserByName($username);

        return ['user_id' => $user->id];
    }

    private static function getRepository(): UserRepository
    {
        return \Yii::$container->get(UserRepository::class);
    }

    private static function getOauth(): Module
    {
        return \Yii::$app->getModule('oauth2');
    }

    private function findActiveUserByName($username): ?User
    {
        return self::getRepository()->findActiveByUsername($username);
    }
}
