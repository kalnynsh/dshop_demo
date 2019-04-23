<?php

namespace shop\forms\manage\User;

use shop\entities\User\User;
use yii\base\Model;
use shop\helpers\UserHelper;

class UserEditForm extends Model
{
    public $username;
    public $email;
    public $role;
    public $phone;
    public $_user;

    public function __construct(User $user, $config = [])
    {
        $this->username = $user->username;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $roles = \Yii::$app->authManager->getRolesByUser($user->id);
        $this->role = $roles ? reset($roles)->name : null;
        $this->_user = $user;

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['username', 'email', 'phone', 'role'], 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['phone', 'integer'],
            [
                ['username', 'email', 'phone'],
                'unique',
                'targetClass' => User::class,
                'filter' => ['<>', 'id', $this->_user->id]
            ],
        ];
    }

    public function rolesList(): array
    {
        return UserHelper::rolesList();
    }
}
