<?php

namespace shop\readModels\Shop;

use yii\helpers\ArrayHelper;
use yii\db\ActiveQuery;
use shop\readModels\Shop\views\CategoryView;
use shop\entities\Shop\Category;

class CategoryReadRepository
{
    public function getRoot(): Category
    {
        return $this->query()->roots()->one();
    }

    public function find($id): ?Category
    {
        return $this->query()
            ->andWhere(['id' => $id])
            ->andWhere(['>', 'depth', 0])
            ->one();
    }

    public function findBySlug($slug): ?Category
    {
        return $this->query()
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

        return array_map(
            function (Category $category) {
                $productCount = $this->getProductCountByCategory($category);

                return new CategoryView($category, $productCount);
            },
            $query->all()
        );
    }

    private function query(): ActiveQuery
    {
        return Category::find();
    }

    private function getQuery()
    {
        return $this->query()
            ->andWhere(['>', 'depth', 0])
            ->orderBy('lft');
    }

    public function getAll(): array
    {
        return $this->getQuery()->all();
    }

    private function getProductCountByCategory(Category $category): int
    {
        return (new ProductReadRepository())->getCountByCategory($category);
    }
}
