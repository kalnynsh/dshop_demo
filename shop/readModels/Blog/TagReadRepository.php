<?php

namespace shop\readModels\Blog;

use shop\entities\Blog\Tag;

class TagReadRepository
{
    /** @property ActiveQuery $query */
    private $query;

    public function __construct()
    {
        $this->query = Tag::find();
    }

    public function findBySlug($slug): ?Tag
    {
        return $this->query->andWhere(['slug' => $slug])->one();
    }
}
