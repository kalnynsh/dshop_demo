<?php

namespace shop\tests\unit\entities\Shop\Brand;

use Codeception\Test\Unit;
use shop\entities\Shop\Brand;
use shop\entities\Meta;

class EditTest extends Unit
{
    public function testSuccess()
    {
        $brand = new Brand([
            'name' => 'Old Brand',
            'slug' => 'old-brand',
        ]);

        $brand->edit(
            $name = 'New Brand',
            $slug = 'new-brand',
            $meta = new Meta(
                'New brand',
                'New brand description',
                'new brand'
            )
        );

        $this->assertEquals($name, $brand->name);
        $this->assertEquals($slug, $brand->slug);
        $this->assertEquals($meta, $brand->meta);
    }
}
