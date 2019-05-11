<?php

namespace shop\repositories\Shop;

use shop\repositories\events\EntitySaved;
use shop\repositories\events\EntityRemoved;
use shop\repositories\NotFoundException;
use shop\entities\Shop\Category;
use shop\dispatchers\IEventDispatcher;

class CategoryRepository
{
    private $dispatcher;

    public function __construct(IEventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function get($id): Category
    {
        if (!$category = Category::findOne($id)) {
            throw new NotFoundException('Category is not found.');
        }

        return $category;
    }

    public function find($id): ?Category
    {
        return Category::findOne($id);
    }

    public function save(Category $category): void
    {
        if (!$category->save()) {
            throw new \RuntimeException('Saving error.');
        }

        $this->dispatcher->dispatch(new EntitySaved($category));
    }

    public function remove(Category $category): void
    {
        if (!$category->delete()) {
            throw new \RuntimeException('Removing error.');
        }

        $this->dispatcher->dispatch(new EntityRemoved($category));
    }
}
