<?php

namespace shop\entities\behaviors;

use Elasticsearch\Client;
use shop\entities\Shop\Category;
use shop\entities\Shop\Product\Product;
use shop\entities\Shop\Product\Value;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class ElasticProductBehavior extends Behavior
{
    /** @property Client $client */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function events(): array
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'onAfterInsert',
            ActiveRecord::EVENT_AFTER_UPDATE => 'onAfterUpdate',
            ActiveRecord::EVENT_BEFORE_DELETE => 'onBeforeDelete',
        ];
    }

    public function onAfterInsert(Event $event): void
    {
        /** @var Product $product */
        $product = $event->sender;

        $this->client->index($this->getParams($product));
    }

    public function onAfterUpdate(Event $event): void
    {
        /** @var Product $product */
        $product = $event->sender;

        $this->client->update($this->getUpdateParams($product));
    }

    public function onBeforeDelete(Event $event): void
    {
        /** @var Product $product */
        $product = $event->sender;

        $this->client->delete([
            'index' => 'shop',
            'type' => 'products',
            'id' => $product->id,
        ]);
    }

    private function getParams(Product $product): array
    {
        return [
            'index' => 'shop',
            'type' => 'products',
            'id' => $product->id,
            'body' => [
                'id' => $product->id,
                'name' => $product->name,
                'code' => $product->code,
                'description' => strip_tags($product->description),
                'price' => (int) $product->price_new,
                'rating' => $product->rating,
                'brand' => (int) $product->brand_id,
                'categories' => $this->getProductCategories($product),
                'tags' => ArrayHelper::getColumn($product->tagAssignments, 'tag_id'),
                'values' => array_map(function (Value $value) {
                    return [
                        'characteristic' => $value->characteristic_id,
                        'value_string' => (string) str_replace('_', ' ', $value->value),
                        'value_int' => (int) $value->value,
                        'value_float' => (float) $value->value,
                    ];
                }, $product->values),
            ],
        ];
    }

    private function getUpdateParams(Product $product): array
    {
        return [
            'index' => 'shop',
            'type' => 'products',
            'id' => $product->id,
            'body' => [
                'doc' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'code' => $product->code,
                    'description' => strip_tags($product->description),
                    'price' => (int) $product->price_new,
                    'rating' => $product->rating,
                    'brand' => (int) $product->brand_id,
                    'categories' => $this->getProductCategories($product),
                    'tags' => ArrayHelper::getColumn($product->tagAssignments, 'tag_id'),
                    'values' => array_map(function (Value $value) {
                        return [
                            'characteristic' => $value->characteristic_id,
                            'value_string' => (string) str_replace('_', ' ', $value->value),
                            'value_int' => (int) $value->value,
                            'value_float' => (float) $value->value,
                        ];
                    }, $product->values),
                ],
            ],
        ];
    }

    private function getProductCategories(Product $product)
    {
        return ArrayHelper::merge(
            [$product->category->id],
            ArrayHelper::getColumn($product->category->parents, 'id'),
            ArrayHelper::getColumn($product->categories, 'id'),
            array_reduce(
                array_map(function (Category $category) {
                    return ArrayHelper::getColumn($category->parents, 'id');
                }, $product->categories),
                'array_merge',
                []
            )
        );
    }
}
