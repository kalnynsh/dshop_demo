<?php

namespace shop\services\auth;

use yii\mail\MailerInterface;
use shop\services\newsletter\MailNewsletter;
use shop\services\manage\access\RoleManagerService;
use shop\services\manage\access\Rbac;
use shop\services\TransactionManager;
use shop\repositories\UserRepository;
use shop\forms\auth\SignupForm;
use shop\entities\User\User;

/**
 * Signup new user
 *
 * @property UserRepository $users
 * @property MailerInterface $mailer
 * @property RoleManagerService $roles
 * @property TransactionManager $transaction
 * @property Componant $yiiApp
 */
class SignupService
{
    private $mailer;
    private $users;
    private $roles;
    private $transaction;
    private $newsletter;
    private $yiiApp;

    public function __construct(
        UserRepository $users,
        MailerInterface $mailer,
        RoleManagerService $roles,
        TransactionManager $transaction,
        MailNewsletter $newsletter
    ) {
        $this->mailer = $mailer;
        $this->users = $users;
        $this->roles = $roles;
        $this->transaction = $transaction;
        $this->newsletter = $newsletter;
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

        $sent = $this->mailer
            ->compose(
                [
                    'html' => 'auth/signup/confirm-html',
                    'text' => 'auth/signup/confirm-text'
                ],
                ['user' => $user]
            )
            ->setTo($form->email)
            ->setSubject('Signup confirm for ' . $this->yiiApp->name)
            ->send();

        if (!$sent) {
            throw new \RuntimeException('Email sending error.');
        }
    }

    public function confirm($token): void
    {
        if (empty($token)) {
            throw new \DomainException('Empty confirm token.');
        }

        $user = $this->users->getByEmailConfirmToken($token);
        $user->confirmSignup();
        $this->users->save($user);
        $this->newsletter->subscribe($user->email);
    }
}
