<?php

namespace shop\forms\manage\Blog;

use shop\entities\Blog\Category;
use shop\forms\CompositeForm;
use shop\forms\manage\MetaForm;
use shop\validators\SlugValidator;

/**
 * CategoryForm class
 *
 * @property string $name
 * @property string $slug
 * @property string $title
 * @property string $description
 * @property int    $sort
 * @property Category $_category
 * @property MetaForm $meta;
 */
class CategoryForm extends CompositeForm
{
    public $name;
    public $slug;
    public $title;
    public $description;
    public $sort;

    private $_category;

    public function __construct(Category $category = null, $config = [])
    {
        if ($category) {
            $this->name = $category->name;
            $this->slug = $category->slug;
            $this->title = $category->title;
            $this->description = $category->description;
            $this->sort = $category->sort;
            $this->meta = new MetaForm($category->meta);
            $this->_category = $category;
        }

        if (!$category) {
            $this->meta = new MetaForm();
            $this->sort = Category::find()->max('sort') + 1;
        }

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name', 'slug'], 'required'],
            [['name', 'slug', 'title'], 'string', 'max' => 255],
            [['description'], 'string'],
            ['slug', SlugValidator::class],
            [
                ['name', 'slug'],
                'unique',
                'targetClass' => Category::class,
                'filter' => ($this->_category ? ['<>', 'id', $this->_category->id] : null),
            ],
        ];
    }

    public function internalForms(): array
    {
        return ['meta'];
    }
}
