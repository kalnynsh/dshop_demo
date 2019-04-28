<?php

namespace shop\jobs\Shop\Product;

use yii\mail\MailerInterface;
use yii\base\ErrorHandler;
use shop\repositories\UserRepository;
use shop\jobs\Shop\Product\ProductAvailabilityNotification;
use shop\entities\User\User;
use shop\entities\Shop\Product\Product;

class ProductAvailabilityNotificationHandler
{
    private $users;
    private $mailer;
    private $errorHandler;

    public function __construct(
        UserRepository $users,
        MailerInterface $mailer,
        ErrorHandler $errorHandler
    ) {
        $this->users = $users;
        $this->mailer = $mailer;
        $this->errorHandler = $errorHandler;
    }

    public function handle(ProductAvailabilityNotification $job): void
    {
        $wishlistUsers = $this->users->getAllByProductInWishlist($job->product->id);

        foreach ($wishlistUsers as $user) {
            if ($user->isActive()) {
                try {
                    $this->sendEmailNotification($user, $job->product);
                } catch (\Exception $error) {
                    $this->errorHandler->handleException($error);
                }
            }
        }
    }

    private function sendEmailNotification(User $user, Product $product): void
    {
        $sent = $this
            ->mailer
            ->compose(
                [
                    'html' => 'shop/wishlist/available-html',
                    'text' => 'shop/wishlist/available-text',
                ],
                [
                    'user' => $user,
                    'product' => $product
                ]
            )
            ->setTo($user->email)
            ->setSubject('Product is available')
            ->send();

        if (!$sent) {
            throw new \RuntimeException('Email sending error to ' . $user->email);
        }
    }
}
