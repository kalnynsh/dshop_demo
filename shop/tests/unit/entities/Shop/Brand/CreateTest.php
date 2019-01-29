<?php

namespace shop\tests\unit\entities\Shop\Brand;

use Codeception\Test\Unit;
use shop\entities\Shop\Brand;
use shop\entities\Meta;

class CreateTest extends Unit
{
    public function testSuccess()
    {
        $brand = Brand::create(
            $name = 'Super brand',
            $slug = 'super-brand',
            $meta = new Meta(
                'Super brand',
                'Super brand description',
                'super brand'
            )
        );

        $this->assertEquals($name, $brand->name);
        $this->assertEquals($slug, $brand->slug);
        $this->assertEquals($meta, $brand->meta);
    }
}
