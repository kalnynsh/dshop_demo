<?php

namespace frontend\widgets\Blog;

use shop\entities\Blog\Category;
use shop\readModels\Blog\CategoryReadRepository;
use yii\base\Widget;
use yii\helpers\Html;

class CategoriesWidget extends Widget
{
    /** @var Category|null $active */
    public $active;

    private $categories;

    public function __construct(
        CategoryReadRepository $categories,
        $config = []
    ) {
        parent::__construct($config);
        $this->categories = $categories;
    }

    public function run(): string
    {
        return Html::tag(
            'div',
            $this->getCategoriesList(),
            [
                'class' => 'list-group',
            ]
        );
    }

    private function getCategoriesList()
    {
        $categoriesList = implode(PHP_EOL, array_map(function (Category $category) {
            $active = $this->isActive($category);

            return Html::a(
                Html::encode($category->name),
                ['/blog/post/category', 'slug' => $category->slug],
                ['class' => $active ? 'list-group-item active' : 'list-group-item']
            );
        }, $this->categories->getAll()));

        return $categoriesList;
    }

    private function isActive(Category $category): bool
    {
        return $this->active
            && ($this->active->id == $category->id);
    }
}
