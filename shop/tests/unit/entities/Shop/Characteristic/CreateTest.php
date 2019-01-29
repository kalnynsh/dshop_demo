<?php

namespace shop\tests\unit\entities\Shop\Characteristic;

use Codeception\Test\Unit;
use shop\entities\Shop\Characteristic;

class CreateTest extends Unit
{
    public function testSuccess()
    {
        $characteristic = Characteristic::create(
            $name = 'Characteristic name',
            $type = Characteristic::TYPE_INTEGER,
            $required = true,
            $default = 0,
            $variants = [1, 12],
            $sort = 13
        );

        $this->assertEquals($name, $characteristic->name);
        $this->assertEquals($type, $characteristic->type);
        $this->assertEquals($required, $characteristic->required);
        $this->assertEquals($default, $characteristic->default);
        $this->assertEquals($variants, $characteristic->variants);
        $this->assertEquals($sort, $characteristic->sort);
        $this->assertTrue($characteristic->isSelect());
        $this->assertTrue($characteristic->isInteger());
    }
}
