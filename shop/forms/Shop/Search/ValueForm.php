<?php

namespace shop\forms\Shop\Search;

use yii\base\Model;
use shop\entities\Shop\Characteristic;

/**
 * ValueForm class
 *
 * @property integer $from
 * @property integer $to
 * @property string  $equal
 */
class ValueForm extends Model
{
    public $from;
    public $to;
    public $equal;

    private $_characteristic;

    public function __construct(
        Characteristic $characteristic,
        $config = []
    ) {
        $this->_characteristic = $characteristic;
        parent::__construct($config);
    }

    public function rules(): array
    {
        return array_filter([
            $this->_characteristic->isString() ? ['equal', 'string'] : false,
            $this->_characteristic->isInteger() ? [['from', 'to'], 'integer'] : false,
            $this->_characteristic->isFloat() ? [['from', 'to'], 'integer'] : false,
        ]);
    }

    public function isFilled(): bool
    {
        return !empty($this->from) || !empty($this->to) || !empty($this->equal);
    }

    public function variantsList(): array
    {
        return $this->_characteristic->variants ?
            array_combine(
                $this->_characteristic->variants,
                $this->_characteristic->variants
            ) : [];
    }

    public function getCharacteristicName(): string
    {
        return $this->_characteristic->name;
    }

    public function getId(): int
    {
        return $this->_characteristic->id;
    }

    public function formName(): string
    {
        return 'v';
    }
}
