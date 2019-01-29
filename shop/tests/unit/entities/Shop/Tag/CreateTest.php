<?php

namespace shop\tests\unit\entities\Shop\Tag;

use Codeception\Test\Unit;
use shop\entities\Shop\Tag;

class CreateTest extends Unit
{
    public function testSuccess()
    {
        $tag = Tag::create(
            $name = 'Super Tag',
            $slug = 'super-tag'
        );

        $this->assertEquals($name, $tag->name);
        $this->assertEquals($slug, $tag->slug);
    }
}
