<?php

namespace shop\tests\unit\entities\Shop\Category;

use Codeception\Test\Unit;
use shop\entities\Shop\Category;
use shop\entities\Meta;

class CreateTest extends Unit
{
    public function testSuccess()
    {
        $category = Category::create(
            $name = 'Category name',
            $slug = 'category-name',
            $title = 'Category title',
            $description = 'Category description',
            $meta = new Meta(
                'Category title',
                'Category description',
                'category name keywords'
            )
        );

        $this->assertEquals($name, $category->name);
        $this->assertEquals($slug, $category->slug);
        $this->assertEquals($title, $category->title);
        $this->assertEquals($description, $category->description);
        $this->assertEquals($meta, $category->meta);
    }
}
