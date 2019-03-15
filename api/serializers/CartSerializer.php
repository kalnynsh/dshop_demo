<?php

namespace api\serializers;

use yii\helpers\Url;
use shop\cart\cost\Discount;
use shop\cart\cost\Cost;
use shop\cart\CartItem;
use shop\cart\Cart;

/**
 * CartSerializer class makes serialization Cart object data
 */
class CartSerializer
{
    public function serializeCartItem(Cart $cart): array
    {
        return [
            'amount' => $cart->getAmount(),
            'weight' => $cart->getWeight(),
            'items' => array_map(function (CartItem $item) {
                $product = $item->getProduct();
                $modification = $item->getModification();

                return [
                    'id' => $item->getId(),
                    'quantity' => $item->getQuantity(),
                    'price' => $item->getPrice(),
                    'cost' => $item->getCost(),
                    'product' => [
                        'id' => $product->id,
                        'code' => $product->code,
                        'name' => $product->name,
                        'thumbnail' => $product->mainPhoto
                        ? $product->mainPhoto->getThumbFileUrl('file', 'cart_list')
                        : null,
                        '_links' => [
                            'self' => [
                                'href' => Url::to([
                                    '/shop/product/view',
                                    'id' => $product->id,
                                ], true),
                            ],
                        ],
                    ],
                    'modification' => $modification ? [
                        'id' => $product->id,
                        'code' => $modification->code,
                        'name' => $modification->name,
                    ] : [],
                    '_links' => [
                        'quantity' => [
                            'href' => Url::to([
                                'quantity',
                                'id' => $item->getId(),
                            ], true),
                        ],
                        // 'remove' => [
                        //     'href' => Url::to([
                        //         'delete',
                        //         'id' => $item->getId(),
                        //     ], true),
                        // ],
                    ],
                ];
            }, $cart->getItems()),
        ];
    }

    public function serializeCartCost(Cost $cost): array
    {
        return [
            'cost' => [
                'origin' => $cost->getOrigin(),
                'discounts' => array_map(function (Discount $discount) {
                    return [
                        'name' => $discount->getName(),
                        'value' => $discount->getValue(),
                    ];
                }, $cost->getDiscounts()),
                'total' => $cost->getTotal(),
            ],
            '_links' => [
                'checkout' => [
                    'href' => Url::to(['/shop/checkout/index'], true)
                ],
            ],
        ];
    }
}
