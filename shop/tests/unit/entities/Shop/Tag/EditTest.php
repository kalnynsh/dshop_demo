<?php

namespace shop\tests\unit\entities\Shop\Tag;

use Codeception\Test\Unit;
use shop\entities\Shop\Tag;

class EditTest extends Unit
{
    public function testSuccess()
    {
        $tag = new Tag([
            'name' => 'Old Tag',
            'slug' => 'old-tag',
        ]);

        $tag->edit(
            $name = 'New Tag',
            $slug = 'new-tag'
        );

        $this->assertEquals($name, $tag->name);
        $this->assertEquals($slug, $tag->slug);
    }
}
