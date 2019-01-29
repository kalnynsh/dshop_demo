<?php

namespace shop\readModels\Blog;

use yii\db\ActiveQuery;
use yii\data\DataProviderInterface;
use yii\data\ActiveDataProvider;
use shop\entities\Blog\Tag;
use shop\entities\Blog\Post\Post;
use shop\entities\Blog\Category;

class PostReadRepository
{
    /** @property PostQuery $query */
    private $pQuery;

    public function __construct()
    {
        $this->pQuery = Post::find();
    }

    public function getAll(): DataProviderInterface
    {
        $query = $this->pQuery->active()->with('category');

        return $this->getProvider($query);
    }

    public function getAllByCategory(Category $category): DataProviderInterface
    {
        $query = $this->pQuery->active()->andWhere(['category_id' => $category->id])->with('category');

        return $this->getProvider($query);
    }

    public function getAllByTag(Tag $tag): DataProviderInterface
    {
        $query = $this->pQuery->alias('p')->active('p')->with('category');
        $query->joinWith(['tagAssignments ta'], false);
        $query->andWhere(['ta.tag_id' => $tag->id]);

        $query->groupBy('p.id');

        return $this->getProvider($query);
    }

    public function getLast($limit): array
    {
        return $this
            ->pQuery
            ->active()
            ->with('category')
            ->orderBy(['id' => SORT_DESC])
            ->limit($limit)
            ->all();
    }

    public function getPopular($limit): array
    {
        return $this
        ->pQuery
        ->active()
        ->with('category')
        ->orderBy(['comments_count' => SORT_DESC])
        ->limit($limit)
        ->all();
    }

    public function find($id): ?Post
    {
        return $this->pQuery->active()->andWhere(['id' => $id])->one();
    }

    private function getProvider(ActiveQuery $query): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
        ]);
    }
}
