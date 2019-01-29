<?php

namespace console\controllers;

use yii\helpers\ArrayHelper;
use yii\console\Controller;
use shop\entities\Shop\Product\Value;
use shop\entities\Shop\Product\Product;
use shop\entities\Shop\Category;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Elasticsearch\Client;

class SearchController extends Controller
{
    private $client;

    public function __construct($id, $module, Client $client, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->client = $client;
    }

    public function actionReindex(): void
    {
        $query = Product::find()
            ->active()
            ->with([
                'category',
                'categoryAssignments',
                'tagAssignments',
                'values'
                ])
            ->orderBy('id');

        $this->displayMessage('Clearing indexes.');

        try {
            $this
                ->client
                ->indices()
                ->delete([
                    'index' => 'shop'
                ]);
        } catch (Missing404Exception $e) {
            $this->displayMessage('Indices are empty.');
        }

        $this->displayMessage('Creating indices.');

        $this->createIndices();

        $this->displayMessage('Products indexing.');

        foreach ($query->each() as $product) {
            /** @var Product $product */
            $this->displayMessage('Product #' . $product->id);
            $this->setProductIndex($product);
        }

        $this->displayMessage('Done!');
    }

    private function displayMessage($message)
    {
        return $this->stdout($message . PHP_EOL);
    }

    private function createIndices()
    {
        return $this->client->indices()->create([
            'index' => 'shop',
            'body' => [
                'mappings' => [
                    'products' => [
                        '_source' => [
                            'enabled' => true,
                        ],
                        'properties' => [
                            'id' => [
                                'type' => 'integer',
                            ],
                            'name' => [
                                'type' => 'text',
                            ],
                            'code' => [
                                'type' => 'text',
                            ],
                            'description' => [
                                'type' => 'text',
                            ],
                            'price' => [
                                'type' => 'integer',
                            ],
                            'rating' => [
                                'type' => 'float',
                            ],
                            'brand' => [
                                'type' => 'integer',
                            ],
                            'categories' => [
                                'type' => 'integer',
                            ],
                            'tags' => [
                                'type' => 'integer',
                            ],
                            'values' => [
                                'type' => 'nested',
                                'properties' => [
                                    'characteristic' => [
                                        'type' => 'integer',
                                    ],
                                    'value_string' => [
                                        'type' => 'keyword',
                                    ],
                                    'value_int' => [
                                        'type' => 'integer',
                                    ],
                                    'value_float' => [
                                        'type' => 'float',
                                    ],
                                ]
                            ],
                        ]
                    ]
                ]
            ]
        ]);
    }

    private function setProductIndex(Product $product)
    {
        return $this->client->index([
            'index' => 'shop',
            'type' => 'products',
            'id' => $product->id,
            'body' => [
                'id' => $product->id,
                'name' => $product->name,
                'code' => $product->code,
                'description' => strip_tags($product->description),
                'price' => (int)$product->price_new,
                'rating' => $product->rating,
                'brand' => (int)$product->brand_id,
                'categories' => $this->getProductCategories($product),
                'tags' => ArrayHelper::getColumn($product->tagAssignments, 'tag_id'),
                'values' => array_map(function (Value $value) {
                    return [
                        'characteristic' => $value->characteristic_id,
                        'value_string' => (string)str_replace('_', ' ', $value->value),
                        'value_int' => (int)$value->value,
                        'value_float' => (float)$value->value,
                    ];
                }, $product->values),
            ]
        ]);
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
