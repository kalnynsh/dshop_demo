<?php

namespace shop\readModels\Shop;

use yii\helpers\ArrayHelper;
use yii\db\Expression;
use yii\db\ActiveQuery;
use yii\data\Sort;
use yii\data\Pagination;
use yii\data\DataProviderInterface;
use yii\data\ActiveDataProvider;
use shop\forms\Shop\Search\ValueForm;
use shop\forms\Shop\Search\SearchForm;
use shop\entities\Shop\Tag;
use shop\entities\Shop\Product\Product;
use shop\entities\Shop\Category;
use shop\entities\Shop\Brand;
use Elasticsearch\Client;

class ProductReadRepository
{
    /** @property Client $client */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getAll(): DataProviderInterface
    {
        $query = $this->getActiveProductWithMainPhotoQuery();

        return $this->getProvider($query);
    }

    public function getAllByCategory(Category $category): DataProviderInterface
    {
        $query = $this->getActiveProductQuery()->with('mainPhoto', 'category');

        $ids = ArrayHelper::merge(
            [$category->id],
            $category->getDescendants()->select('id')->column()
        );

        $query->joinWith(['categoryAssignments cas'], false);

        $query->andWhere([
            'or',
            ['p.category_id' => $ids],
            ['cas.category_id' => $ids]
        ]);

        $query->groupBy('p.id');

        return $this->getProvider($query);
    }

    public function getAllByBrand(Brand $brand): DataProviderInterface
    {
        $query = $this->getActiveProductWithMainPhotoQuery();
        $query->andWhere(['p.brand_id' => $brand->id]);

        return $this->getProvider($query);
    }

    public function getAllByTag(Tag $tag): DataProviderInterface
    {
        $query = $this->getActiveProductWithMainPhotoQuery();

        $query->joinWith(['tagAssignments ta'], false);
        $query->andWhere(['ta.tag_id' => $tag->id]);
        $query->groupBy('p.id');

        return $this->getProvider($query);
    }

    public function getFeatured($limit): array
    {
        return $this->getActiveProductWithMainPhotoQuery()
            ->orderBy(['id' => SORT_DESC])
            ->limit($limit)
            ->all();
    }

    public function find($id): ?Product
    {
        return Product::find()->active()->andWhere(['id' => $id])->one();
    }

    private function getProvider(ActiveQuery $query): DataProviderInterface
    {
        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
                'attributes' => [
                    'id' => [
                        'asc' => ['p.id' => SORT_ASC],
                        'desc' => ['p.id' => SORT_DESC],
                    ],
                    'name' => [
                        'asc' => ['p.name' => SORT_ASC],
                        'desc' => ['p.name' => SORT_DESC],
                    ],
                    'price' => [
                        'asc' => ['p.price_new' => SORT_ASC],
                        'desc' => ['p.price_new' => SORT_DESC],
                    ],
                    'rating' => [
                        'asc' => ['p.rating' => SORT_ASC],
                        'desc' => ['p.rating' => SORT_DESC],
                    ],
                ],
            ],
            'pagination' => [
                'pageSizeLimit' => [15, 100],
            ],
        ]);
    }

    public function search(SearchForm $form): DataProviderInterface
    {
        $pagination = $this->getPagination();
        $sort = $this->getSort();
        $response = $this->getClientResponse($pagination, $sort, $form);

        $ids = ArrayHelper::getColumn($response['hits']['hits'], '_source.id');

        if ($ids) {
            $query = $this
                ->getActiveProductWithMainPhotoQuery()
                ->andWhere(['p.id' => $ids])
                ->orderBy(
                    new Expression('FIELD(id,' . implode(',', $ids) . ')')
                );
        }

        if (!$ids) {
            $query = Product::find()->andWhere(['id' => 0]);
        }

        return $this->getSimpleActiveDataProvider([
            'query' => $query,
            'totalCount' => $response['hits']['total'],
            'pagination' => $pagination,
            'sort' => $sort,
        ]);
    }

    private function getSimpleActiveDataProvider(array $params): SimpleActiveDataProvider
    {
        return new SimpleActiveDataProvider($params);
    }

    private function getActiveProductQuery(): ActiveQuery
    {
        return Product::find()->alias('p')->active('p');
    }

    private function getActiveProductWithMainPhotoQuery(): ActiveQuery
    {
        return $this->getActiveProductQuery()->with('mainPhoto');
    }

    private function getPagination()
    {
        return new Pagination([
            'pageSizeLimit' => [15, 100],
            'validatePage' => false,
        ]);
    }

    private function getSort()
    {
        return new Sort([
            'defaultOrder' => ['id' => SORT_DESC],
            'attributes' => [
                'id',
                'name',
                'price',
                'rating',
            ],
        ]);
    }

    private function getClientResponse(
        Pagination $pagination,
        Sort $sort,
        SearchForm $form
    ) {
        $response = $this->client->search([
            'index' => 'shop',
            'type' => 'products',
            'body' => [
                '_source' => ['id'],
                'from' => $pagination->getOffset(),
                'size' => $pagination->getLimit(),
                'sort' => array_map(function ($attribute, $direction) {
                    return [
                        $attribute => [
                            'order' => ($direction === SORT_ASC ? 'asc' : 'desc'),
                        ],
                    ];
                }, array_keys($sort->getOrders()),
                    $sort->getOrders()
                ),
                'query' => [
                    'bool' => [
                        'must' => array_merge(
                            array_filter([
                                !empty($form->category) ? ['term' => ['category' => $form->category]] : false,
                                !empty($form->brand) ? ['term' => ['brand' => $form->brand]] : false,
                                !empty($form->text) ? [
                                    'multi_match' => [
                                        'query' => $form->text,
                                        'fields' => [
                                            'name^3',
                                            'description',
                                        ],
                                    ],
                                ] : false,
                            ]),
                            array_map(function (ValueForm $value) {
                                return [
                                    'nested' => [
                                        'path' => 'values',
                                        'query' => [
                                            'bool' => [
                                                'must' => array_filter([
                                                    [
                                                        'match' => ['values.characteristic' => $value->getId()],
                                                    ],
                                                    !empty($value->equal) ? [
                                                            'match' => ['values.value_string' => $value->equal]
                                                        ] : false,
                                                    !empty($value->from) ? [
                                                            'range' => [
                                                                'values.value_int' => ['gte' => $value->from]
                                                            ]
                                                        ] : false,
                                                    !empty($value->to) ? [
                                                            'range' => [
                                                                'values.value_int' => ['lte' => $value->to]
                                                            ]
                                                        ] : false,
                                                ])
                                            ]
                                        ]
                                    ]
                                ];
                            }, array_filter(
                                $form->values,
                                function (ValueForm $value) {
                                    return $value->isFilled();
                                }
                            ))
                        ),
                    ],
                ],
            ],
        ]);

        return $response;
    }

    public function getWishlist($userId): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => Product::find()
                ->alias('p')
                ->active('p')
                ->joinWith('wishlistItems w', false, 'INNER JOIN')
                ->andWhere(['w.user_id' => $userId]),
            'sort' => false,
        ]);
    }
}
