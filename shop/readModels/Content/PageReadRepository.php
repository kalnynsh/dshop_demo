<?php

namespace shop\readModels\Content;

use shop\entities\Content\queries\PageQuery;
use shop\entities\Content\Page;

class PageReadRepository
{
    public function find($id): ?Page
    {
        return $this->query()
            ->andWhere(['id' => $id])
            ->andWhere(['>', 'depth', 0])
            ->one();
    }

    public function findBySlug($slug): ?Page
    {
        return $this->query()
            ->andWhere(['slug' => $slug])
            ->andWhere(['>', 'depth', 0])
            ->one();
    }

    private function query(): PageQuery
    {
        return Page::find();
    }
}
