<?php

namespace shop\repositories\Blog;

use yii\db\ActiveQuery;
use shop\repositories\NotFoundException;
use shop\entities\Blog\Tag;

class TagRepository
{
    public function get($id): Tag
    {
        if (!$tag = $this->query()->andWhere(['id' => $id])->one()) {
            throw new NotFoundException('Tag is not found.');
        }

        return $tag;
    }

    public function findByName($name): ?Tag
    {
        return $this->query()->andWhere(['name' => $name])->one();
    }

    public function find($id): ?Tag
    {
        return $this->query()->andWhere(['id' => $id])->one();
    }

    public function save(Tag $tag): void
    {
        if (!$tag->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    public function remove(Tag $tag): void
    {
        if (!$tag->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }

    private function query(): ActiveQuery
    {
        return Tag::find();
    }
}
