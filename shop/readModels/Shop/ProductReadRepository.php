<?php

namespace shop\readModels\Shop;

use yii\helpers\ArrayHelper;
use yii\db\Expression;
use yii\db\ActiveQuery;
use yii\data\Sort;
use yii\data\Pagination;
use yii\data\DataProviderInterface;
use yii\data\ActiveDataProvider;
use shop\repositories\Shop\CategoryRepository;
use shop\forms\Shop\Search\SearchForm;
use shop\entities\Shop\Tag;
use shop\entities\Shop\Product\Value;
use shop\entities\Shop\Product\Product;
use shop\entities\Shop\Category;
use shop\entities\Shop\Brand;
use Elasticsearch\Client;

class ProductReadRepository
{
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
            ['cas.category_id' => $ids],
        ]);

        $query->groupBy('p.id');

        return $this->getProvider($query);
    }

    public function getCountByCategory(Category $category): int
    {
        $query = $this->getActiveProductQuery()->with('category');

        $ids = ArrayHelper::merge(
            [$category->id],
            $category->getDescendants()->select('id')->column()
        );

        $query->joinWith(['categoryAssignments cas'], false);

        $query->andWhere([
            'or',
            ['p.category_id' => $ids],
            ['cas.category_id' => $ids],
        ]);

        $query->groupBy('p.id');

        return (int) $query->count();
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

    public function getAllIterator(): iterable
    {
        return $this->getActiveProductQuery()
            ->with(['mainPhoto', 'brand'])
            ->each();
    }

    public function find($id): ?Product
    {
        return Product::find()->active()->andWhere(['id' => $id])->one();
    }

    public function mysqlSearch(SearchForm $form): DataProviderInterface
    {
        $query = $this->getActiveProductWithMainPhotoQuery();

        if ($form->brand) {
            $query->andWhere(['p.brand_id' => $form->brand]);
        }

        if ($categoryId = $form->category) {
            if ($category = $this->findCategory($categoryId)) {
                $ids = ArrayHelper::merge(
                    [$categoryId],
                    $category->getChildren()->select('id')->column()
                );

                $query->joinWith(['categoryAssignments cas'], false);

                $query->andWhere([
                    'or',
                    ['p.category_id' => $ids],
                    ['cas.category_id' => $ids],
                ]);
            }
        }

        if (!$categoryId) {
            $query->andWhere(['p.id' => 0]);
        }

        if ($form->values) {
            $productIds = null;

            foreach ($form->values as $value) {
                if ($value->isFilled()) {
                    $q = $this
                        ->getValueQuery()
                        ->andWhere(['characteristic_id' => $value->getId()]);

                    $q->andFilterWhere(
                        ['>=', 'CAST(value AS SIGNED)', $value->from]
                    );

                    $q->andFilterWhere(
                        ['<=', 'CAST(value AS SIGNED)', $value->to]
                    );

                    $q->andFilterWhere(['value' => $value->equal]);

                    $foundIds = $q->select('product_id')->column();

                    $productIds = null === $productIds ?
                    $foundIds : array_intersect($productIds, $foundIds);
                }
            }

            if (null !== $productIds) {
                $query->andWhere(['p.id' => $productIds]);
            }
        }

        if (!empty($form->text)) {
            $query->andWhere([
                'or',
                ['like', 'code', $form->text],
                ['like', 'name', $form->text],
            ]);
        }

        $query->groupBy('p.id');

        return $this->getProvider($query);
    }

    public function search(SearchForm $form): DataProviderInterface
    {
        $pagination = new Pagination([
            'pageSizeLimit' => [15, 100],
            'validatePage'  => false,
        ]);

        $sort = new Sort([
            'defaultOrder' => ['id' => SORT_DESC],
            'attributes'   => [
                'id',
                'name',
                'price',
                'rating',
            ],
        ]);

        $response = $this->client->search([
            'index' => 'shop',
            'type'  => 'products',
            'body'  => [
                '_source' => ['id'],
                'from'    => $pagination->getOffset(),
                'size'    => $pagination->getLimit(),
                'sort'    => array_map(function ($attribute, $direction) {
                    return [$attribute => ['order' => SORT_ASC === $direction ? 'asc' : 'desc']];
                }, array_keys($sort->getOrders()), $sort->getOrders()),
                'query'   => [
                    'bool' => [
                        'must' => array_merge(
                            array_filter([
                                !empty($form->category) ? ['term' => ['categories' => $form->category]] : false,
                                !empty($form->brand) ? ['term' => ['brand' => $form->brand]] : false,
                                !empty($form->text) ? ['multi_match' => [
                                    'query'  => $form->text,
                                    'fields' => ['name^3', 'description'],
                                ]] : false,
                            ]),
                            array_map(function (ValueForm $value) {
                                return ['nested' => [
                                    'path'  => 'values',
                                    'query' => [
                                        'bool' => [
                                            'must' => array_filter([
                                                [
                                                    'match' => [
                                                        'values.characteristic' => $value->getId(),
                                                    ],
                                                ],
                                                !empty($value->equal) ? [
                                                    'match' => ['values.value_string' => $value->equal],
                                                ] : false,
                                                !empty($value->from) ? [
                                                    'range' => [
                                                        'values.value_int' => ['gte' => $value->from],
                                                    ],
                                                ] : false,
                                                !empty($value->to) ? [
                                                    'range' => [
                                                        'values.value_int' => [
                                                            'lte' => $value->to,
                                                        ],
                                                    ],
                                                ] : false,
                                            ]),
                                        ],
                                    ],
                                ]];
                            }, array_filter($form->values, function (ValueForm $value) {
                                return $value->isFilled();
                            }))
                        ),
                    ],
                ],
            ],
        ]);

        $ids = ArrayHelper::getColumn($response['hits']['hits'], '_source.id');

        if ($ids) {
            $query = Product::find()
                ->active()
                ->with('mainPhoto')
                ->andWhere(['id' => $ids])
                ->orderBy(new Expression('FIELD(id,' . implode(',', $ids) . ')'));
        } else {
            $query = Product::find()->andWhere(['id' => 0]);
        }

        return new SimpleActiveDataProvider([
            'query'      => $query,
            'totalCount' => $response['hits']['total'],
            'pagination' => $pagination,
            'sort'       => $sort,
        ]);
    }

    private function getActiveProductQuery(): ActiveQuery
    {
        return Product::find()->alias('p')->active('p');
    }

    private function getActiveProductWithMainPhotoQuery(): ActiveQuery
    {
        return $this->getActiveProductQuery()->with('mainPhoto');
    }

    public function getWishlist($userId): ActiveDataProvider
    {
        return $this->getActiveDataProvider([
            'query' => $this->getActiveProductQuery()
                ->joinWith('wishlistItems w', false, 'INNER JOIN')
                ->andWhere(['w.user_id' => $userId]),
            'sort'  => false,
        ]);
    }

    public function count(): int
    {
        return $this->getActiveProductQuery()->count();
    }

    public function getAllByRange(int $offset, int $limit): array
    {
        return $this
            ->getActiveProductQuery()
            ->orderBy(['id' => SORT_ASC])
            ->limit($limit)
            ->offset($offset)
            ->all();
    }

    private function findCategory($catId): ?Category
    {
        return (new CategoryRepository())->find($catId);
    }

    private function getValueQuery(): ActiveQuery
    {
        return Value::find();
    }

    private function getProvider($query): DataProviderInterface
    {
        return new ActiveDataProvider([
            'query'      => $query,
            'sort'       => [
                'defaultOrder' => ['id' => SORT_DESC],
                'attributes'   => [
                    'id'     => [
                        'asc'  => ['p.id' => SORT_ASC],
                        'desc' => ['p.id' => SORT_DESC],
                    ],
                    'name'   => [
                        'asc'  => ['p.name' => SORT_ASC],
                        'desc' => ['p.name' => SORT_DESC],
                    ],
                    'price'  => [
                        'asc'  => ['p.price_new' => SORT_ASC],
                        'desc' => ['p.price_new' => SORT_DESC],
                    ],
                    'rating' => [
                        'asc'  => ['p.rating' => SORT_ASC],
                        'desc' => ['p.rating' => SORT_DESC],
                    ],
                ],
            ],
            'pagination' => [
                'pageSizeLimit' => [15, 100],
            ],
        ]);
    }

    private function getActiveDataProvider(array $params): ActiveDataProvider
    {
        return new ActiveDataProvider($params);
    }
}
