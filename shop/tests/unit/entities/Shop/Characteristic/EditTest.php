<?php

namespace shop\tests\unit\entities\Shop\Characteristic;

use Codeception\Test\Unit;
use shop\entities\Shop\Characteristic;

class EditTest extends Unit
{
    public function testSuccess()
    {
        $characteristic = new Characteristic([
            'name' => 'Characteristic old name',
            'type' => Characteristic::TYPE_INTEGER,
            'required' => false,
            'default' => 10,
            'variants' => [11, 110],
            'sort' => 9
        ]);

        $characteristic->edit(
            $name = 'Characteristic new name',
            $type = Characteristic::TYPE_INTEGER,
            $required = true,
            $default = 1,
            $variants = [1, 10],
            $sort = 10
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
