<?php

namespace shop\repositories\Blog;

use shop\entities\Blog\Category;
use shop\repositories\NotFoundException;
use yii\db\ActiveQuery;

class CategoryRepository
{
    public function get($id): Category
    {
        if (!$category = $this->findOne($id)) {
            throw new NotFoundException('Category is not found.');
        }

        return $category;
    }

    public function find($id): ?Category
    {
        return $this->findOne($id);
    }

    public function save(Category $category): void
    {
        if (!$category->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    public function remove(Category $category): void
    {
        if (!$category->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }

    private function query(): ActiveQuery
    {
        return Category::find();
    }

    private function findOne($id): ?Category
    {
        return $this->query()->andWhere(['id' => $id])->one();
    }
}
