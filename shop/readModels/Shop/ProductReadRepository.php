<?php

namespace shop\readModels\Shop;

use yii\helpers\ArrayHelper;
use yii\db\ActiveQuery;
use yii\data\DataProviderInterface;
use yii\data\ActiveDataProvider;
use shop\repositories\Shop\CategoryRepository;
use shop\forms\Shop\Search\SearchForm;
use shop\entities\Shop\Tag;
use shop\entities\Shop\Product\Value;
use shop\entities\Shop\Product\Product;
use shop\entities\Shop\Category;
use shop\entities\Shop\Brand;

class ProductReadRepository
{
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
            ['cas.category_id' => $ids]
        ]);

        $query->groupBy('p.id');

        return (int)$query->count();
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

    public function search(SearchForm $form): DataProviderInterface
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
                    ['cas.category_id' => $ids]
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

                    $productIds = $productIds === null ?
                        $foundIds : array_intersect($productIds, $foundIds);
                }
            }

            if ($productIds !== null) {
                $query->andWhere(['p.id' => $productIds]);
            }
        }

        if (!empty($form->text)) {
            $query->andWhere([
                'or',
                ['like', 'code', $form->text],
                ['like', 'name', $form->text]
            ]);
        }

        $query->groupBy('p.id');

        return $this->getProvider($query);
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
            'sort' => false,
        ]);
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

    private function getActiveDataProvider(array $params): ActiveDataProvider
    {
        return new ActiveDataProvider($params);
    }
}
