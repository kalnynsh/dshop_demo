<?php

namespace shop\readModels\Shop;

use yii\helpers\ArrayHelper;
use shop\readModels\Shop\views\CategoryView;
use shop\entities\Shop\Category;
use Elasticsearch\Client;

class CategoryReadRepository
{
    /** @property Client $client */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getRoot(): Category
    {
        return Category::find()->roots()->one();
    }

    public function find($id): ?Category
    {
        return Category::find()
            ->andWhere(['id' => $id])
            ->andWhere(['>', 'depth', 0])
            ->one();
    }

    public function findBySlug($slug): ?Category
    {
        return Category::find()
            ->andWhere(['slug' => $slug])
            ->andWhere(['>', 'depth', 0])
            ->one();
    }

    public function getTreeWithSubsOf(Category $category = null): array
    {
        $query = $this->getQuery();

        if ($category) {
            $criteria = ['or', ['depth' => 1]];

            $selfAndParents = ArrayHelper::merge(
                [$category],
                $category->parents
            );

            foreach ($selfAndParents as $item) {
                $criteria[]  = [
                    'and',
                    ['>', 'lft', $item->lft],
                    ['<', 'rgt', $item->rgt],
                    ['depth' => $item->depth + 1]
                ];
            }

            $query->andWhere($criteria);
        }

        if (!$category) {
            $query->andWhere(['depth' => 1]);
        }

        $counts = $this->getCategoriesCountArray();

        return array_map(
            function (Category $category) use ($counts) {
                $categoryCount = $this->getCountOfCategory($counts, $category);

                return new CategoryView($category, $categoryCount);
            },
            $query->all()
        );
    }

    public function getAll(): array
    {
        return $this->getQuery()->all();
    }

    private function getQuery()
    {
        return Category::find()
            ->andWhere(['>', 'depth', 0])
            ->orderBy('lft');
    }

    private function getCategoryAggs()
    {
        return $this->client->search([
            'index' => 'shop',
            'type' => 'products',
            'body' => [
                'size' => 0,
                'aggs' => [
                    'group_by_category' => [
                        'terms' => [
                            'field' => 'categories',
                        ],
                    ],
                ],
            ],
        ]);
    }

    private function getCategoriesCountArray()
    {
        $aggs = $this->getCategoryAggs();

        $counts = ArrayHelper::map(
            $aggs['aggregations']['group_by_category']['buckets'],
            'key',
            'doc_count'
        );

        return $counts;
    }

    private function getCountOfCategory($counts, Category $category)
    {
        return ArrayHelper::getValue($counts, $category->id, 0);
    }
}
