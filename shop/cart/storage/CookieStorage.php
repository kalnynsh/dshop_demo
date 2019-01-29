<?php

namespace shop\cart\storage;

use yii\web\Cookie;
use yii\helpers\Json;
use shop\repositories\Shop\ProductRepository;
use shop\entities\Shop\Product\Product;
use shop\cart\storage\StorageInterface;
use shop\cart\CartItem;

class CookieStorage implements StorageInterface
{
    private $key;
    private $timeout;
    private $yiiApp;
    private $productRepository;

    public function __construct($key, $timeout)
    {
        $this->key = $key;
        $this->timeout = $timeout;
        $this->yiiApp = \Yii::$app;
        $this->productRepository = new ProductRepository();
    }

    public function load(): array
    {
        if ($cookie = $this->yiiApp->request->cookies->get($this->key)) {
            return array_filter(array_map(function (array $row) {
                if (isset($row['p'], $row['q'])
                    && $product = $this->productRepository->findOneActive($row['p'])
                ) {
                    /** @var Product $product */
                    return $this->createCartItem($product, $row['m'] ?? null, $row['q']);
                }

                return false;
            }, $this->decode($cookie->value)));
        }

        return [];
    }

    public function save(array $items): void
    {
        $this->yiiApp->response->cookies->add(
            $this->createCookie([
                'name' => $this->key,
                'value' => $this->encode(array_map(function (CartItem $item) {
                    return [
                        'p' => $item->getProductId(),
                        'm' => $item->getModificationId(),
                        'q' => $item->getQuantity(),
                    ];
                }, $items)),
                'expire' => time() + $this->timeout,
            ])
        );
    }

    private function encode(array $values): string
    {
        return Json::encode($values);
    }

    private function decode($values): array
    {
        return Json::decode($values);
    }

    private function createCartItem(
        Product $product,
        $modificationId,
        $quantity
    ): CartItem {
        return new CartItem($product, $modificationId, $quantity);
    }

    private function createCookie(array $data): Cookie
    {
        return new Cookie($data);
    }
}
