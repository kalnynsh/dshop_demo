<?php

namespace shop\readModels\Blog;

use shop\entities\Blog\Category;

class CategoryReadRepository
{
    /** @property ActiveQuery $query */
    private $query;

    public function __construct()
    {
        $this->query = Category::find();
    }

    public function getAll(): array
    {
        return $this->query->orderBy('sort')->all();
    }

    public function find($id): ?Category
    {
        return $this->query->andWhere(['id' => $id])->one();
    }

    public function findBySlug($slug): ?Category
    {
        return $this->query->andWhere(['slug' => $slug])->one();
    }
}
