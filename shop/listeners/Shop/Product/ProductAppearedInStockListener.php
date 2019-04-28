<?php

namespace shop\listeners\Shop\Product;

use yii\mail\MailerInterface;
use yii\base\ErrorHandler;
use shop\repositories\UserRepository;
use shop\entities\User\User;
use shop\entities\Shop\Product\events\ProductAppearedInStock;
use shop\entities\Shop\Product\Product;

class ProductAppearedInStockListener
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

    public function handle(ProductAppearedInStock $event): void
    {
        if ($event->product->isActive()) {
            $wishlistUsers = $this->users->getAllByProductInWishlist($event->product->id);

            foreach ($wishlistUsers as $user) {
                if ($user->isActive()) {
                    try {
                        $this->sendEmailNotification($user, $event->product);
                    } catch (\Exception $error) {
                        $this->errorHandler->handleException($error);
                    }
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
