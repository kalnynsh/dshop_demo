<?php

namespace shop\services\manage\Blog;

use shop\repositories\NotFoundException;
use shop\repositories\Blog\PostRepository;
use shop\repositories\Blog\CategoryRepository;
use shop\forms\manage\Blog\CategoryForm;
use shop\entities\Meta;
use shop\entities\Blog\Category;

/**
 * CategoryManageService manage Category entity
 *
 * @property CategoryRepository $categories
 * @property PostRepository $posts
 */
class CategoryManageService
{
    private $categories;
    private $posts;

    public function __construct(
        CategoryRepository $categories,
        PostRepository $posts
    ) {
        $this->categories = $categories;
        $this->posts = $posts;
    }

    public function create(CategoryForm $form): Category
    {
        $category = Category::create(
            $form->name,
            $form->slug,
            $form->title,
            $form->description,
            $form->sort,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );

        $this->categories->save($category);

        return $category;
    }

    public function edit($id, CategoryForm $form): void
    {
        $category = $this->categories->get($id);

        $category->edit(
            $form->name,
            $form->slug,
            $form->title,
            $form->description,
            $form->sort,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );

        $this->categories->save($category);
    }

    public function moveUp($catId): void
    {
        $category = $this->categories->get($catId);
        $category->movePrev();

        $this->categories->save($category);
    }

    public function moveDown($catId): void
    {
        $category = $this->categories->get($catId);
        $category->moveNext();

        $this->categories->save($category);
    }

    /**
     * Remove Category class object by id
     *
     * @param int $catId
     * @return void
     * @throws \RuntimeException
     */
    public function remove($catId): void
    {
        $category = $this->categories->get($catId);

        if ($this->posts->existsByCategory($category->id)) {
            throw new \DomainException('Unable removing category with posts.');
        }

        $this->categories->remove($category);
    }

    /**
     * Get Category class object by id
     *
     * @param int $catId
     * @return Category
     * @throws NotFoundException
     */
    public function get($catId): Category
    {
        return $this->categories->get($catId);
    }

    /**
     * Find Category class object by id
     *
     * @param int $catId
     * @return Category
     * @throws NotFoundException
     */
    public function find($catId): ?Category
    {
        return $this->categories->find($catId);
    }
}
