<?php

namespace api\serializers;

use shop\entities\Shop\Category;
use shop\entities\Shop\Product\Modification;
use shop\entities\Shop\Product\Photo;
use shop\entities\Shop\Product\Product;
use shop\entities\Shop\Tag;
use yii\helpers\Url;

/**
 * ProductSerializer class makes serialization Product object data
 */
class ProductSerializer
{
    public function serializeListItem(Product $product): array
    {
        return [
            'id' => $product->id,
            'code' => $product->code,
            'name' => $product->name,
            'category' => [
                'id' => $product->category->id,
                'name' => $product->category->name,
                '_links' => [
                    'self' => [
                        'href' => Url::to(['category', 'id' => $product->category->id], true),
                    ],
                ],
            ],
            'brand' => [
                'id' => $product->brand->id,
                'name' => $product->brand->name,
                '_links' => [
                    'self' => [
                        'href' => Url::to(['brand', 'id' => $product->brand->id], true),
                    ],
                ],
            ],
            'price' => [
                'new' => $product->price_new,
                'old' => $product->price_old,
            ],
            'thumbnail' => (
                $product->mainPhoto
                ? $product->mainPhoto->getThumbFileUrl('file', 'catalog_list') : null
            ),
            '_links' => [
                'self' => [
                    'href' => Url::to(['view', 'id' => $product->id], true),
                ],
                'wish' => [
                    'href' => Url::to(['/shop/wishlist/add', 'id' => $product->id], true),
                ],
                'cart' => [
                    'href' => Url::to(['/shop/cart/add', 'id' => $product->id], true),
                ],
            ],
        ];
    }

    public function serializeView(Product $product): array
    {
        return [
            'id' => $product->id,
            'code' => $product->code,
            'name' => $product->name,
            'description' => $product->description,
            'categories' => [
                'main' => [
                    'id' => $product->category->id,
                    'name' => $product->category->name,
                    '_links' => [
                        'self' => [
                            'href' => Url::to([
                                'category',
                                'id' => $product->category->id,
                            ], true),
                        ],
                    ],
                ],
                'other' => array_map(function (Category $category) {
                    return [
                        'id' => $product->category->id,
                        'name' => $product->category->name,
                        '_links' => [
                            'self' => [
                                'href' => Url::to([
                                    'category',
                                    'id' => $product->category->id,
                                ], true),
                            ],
                        ],
                    ];
                }, $product->categories),
            ],
            'brand' => [
                'id' => $product->brand->id,
                'name' => $product->brand->name,
                '_links' => [
                    'self' => [
                        'href' => Url::to(['brand', 'id' => $product->brand->id], true),
                    ],
                ],
            ],
            'tags' => aray_map(function (Tag $tag) {
                return [
                    'id' => $tag->id,
                    'name' => $tag->name,
                    '_links' => [
                        'self' => [
                            'href' => Url::to([
                                'tag',
                                'id' => $tag->id,
                            ], true),
                        ],
                    ],
                ];
            }, $product->tags),
            'price' => [
                'new' => $product->price_new,
                'old' => $product->price_old,
            ],
            'photos' => array_map(function (Photo $photo) {
                return [
                    'thumbnail' =>
                    $photo->getThumbFileUrl('file', 'catalog_list'),
                    'origin' =>
                    $photo->getThumbFileUrl('file', 'catalog_origin'),
                ];
            }, $product->photos),
            'modifications' => array_map(function (Modification $modification) use ($product) {
                return [
                    'id' => $modification->id,
                    'code' => $modification->code,
                    'name' => $modification->name,
                    'price' => $product->getModificationPrice($modification->id),
                ];
            }, $product->modifications),
            'rating' => $product->rating,
            'weight' => $product->weight,
            'quantity' => $product->quantity,
            '_links' => [
                'self' => ['href' => Url::to(['view', 'id' => $product->id], true)],
                'wish' => ['href' => Url::to(['/shop/wishlist/add', 'id' => $product->id], true)],
                'cart' => ['href' => Url::to(['/shop/cart/add', 'id' => $product->id], true)],
            ],
        ];
    }

    public function serializeWishList(Product $product): array
    {
        return [
            'id' => $product->id,
            'code' => $product->code,
            'name' => $product->name,
            'price' => [
                'new' => $product->price_new,
                'old' => $product->price_old,
            ],
            'thumbnail' => (
                $product->mainPhoto
                ? $product->mainPhoto->getThumbFileUrl('file', 'cart_list') : null
            ),
            '_links' => [
                'self' => ['href' => Url::to(['/shop/product/view', 'id' => $product->id], true)],
                'cart' => ['href' => Url::to(['/shop/cart/add', 'id' => $product->id], true)],
            ],
        ];
    }
}
