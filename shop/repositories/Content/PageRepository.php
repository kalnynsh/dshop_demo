<?php

namespace shop\repositories\Content;

use shop\repositories\NotFoundException;
use shop\entities\Content\queries\PageQuery;
use shop\entities\Content\Page;

class PageRepository
{
    public function get($id): Page
    {
        if ($id == 1) {
            $page = $this
            ->query()
            ->andWhere(['id' => $id])
            ->one();
        } else {
            $page = $this->find($id);
        }

        if (!$page) {
            throw new NotFoundException('Page was not found.');
        }

        return $page;
    }

    public function save(Page $page): void
    {
        if (!$page->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    public function remove(Page $page): void
    {
        if (!$page->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }

    public function find($id): ?Page
    {
        return $this->query()
            ->andWhere(['id' => $id])
            ->andWhere(['>', 'depth', 0])
            ->one();
    }

    private function query(): PageQuery
    {
        return Page::find();
    }
}
