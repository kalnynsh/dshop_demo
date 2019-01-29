<?php

namespace frontend\widgets\Shop;

use shop\entities\Shop\Category;
use shop\readModels\Shop\CategoryReadRepository;
use yii\base\Widget;
use yii\bootstrap\Html;
use shop\readModels\Shop\views\CategoryView;

class CategoriesWidget extends Widget
{
    /** @var Category|null */
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
        $categoriesList = implode(PHP_EOL, array_map(function (CategoryView $view) {
            $indent = $this->calculateIndent($view);
            $active = $this->isActive($view);

            return Html::a(
                $indent . Html::encode($view->category->name) . ' (' . $view->count . ')',
                ['/shop/catalog/category', 'id' => $view->category->id],
                ['class' => $active ? 'list-group-item active' : 'list-group-item']
            );
        }, $this->categories->getTreeWithSubsOf($this->active)));

        return $categoriesList;
    }

    private function calculateIndent(CategoryView $view)
    {
        return (
            $view->category->depth > 1
            ? str_repeat('&nbsp;&nbsp;&nbsp;', $view->category->depth - 1) . '- '
            : ''
        );
    }

    private function isActive($view): bool
    {
        return $this->active
            && ($this->active->id == $view->category->id
                || $this->active->isChildOf($view->category));
    }
}
