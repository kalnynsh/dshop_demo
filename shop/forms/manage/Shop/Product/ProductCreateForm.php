<?php

namespace shop\forms\manage\Shop\Product;

use shop\entities\Shop\Characteristic;
use shop\entities\Shop\Product\Product;
use shop\forms\CompositeForm;
use shop\forms\manage\MetaForm;
use shop\forms\manage\Shop\Product\QuantityForm;
use shop\helpers\BrandsHelper;

/**
 * @property PriceForm      $price
 * @property QuantityForm   $quantity
 * @property MetaForm       $meta
 * @property CategoriesForm $categories
 * @property PhotosForm     $photos
 * @property TagsForm       $tags
 * @property ValueForm[]    $values
 * @property integer        $brandId
 * @property string         $code
 * @property string         $name
 * @property string         $description
 * @property integer        $weight
 */
class ProductCreateForm extends CompositeForm
{
    public $brandId;
    public $code;
    public $name;
    public $description;
    public $weight;

    public function __construct($config = [])
    {
        $this->price = new PriceForm();
        $this->quantity = new QuantityForm();
        $this->meta = new MetaForm();
        $this->categories = new CategoriesForm();
        $this->photos = new PhotosForm();
        $this->tags = new TagsForm();

        $this->values = array_map(
            function (Characteristic $characteristic) {
                return new ValueForm($characteristic);
            },
            Characteristic::find()->orderBy('sort')->all()
        );

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['brandId', 'code', 'name', 'weight'], 'required'],
            [['code', 'name'], 'string', 'max' => 255],
            ['brandId', 'integer'],
            ['code', 'unique', 'targetClass' => Product::class],
            ['description', 'string'],
            ['weight', 'integer', 'min' => 0],
            ['weight', 'default', 'value' => 0],
        ];
    }

    public function brandsList(): array
    {
        return BrandsHelper::list();
    }

    protected function internalForms(): array
    {
        return [
            'price',
            'quantity',
            'meta',
            'photos',
            'categories',
            'tags',
            'values',
        ];
    }
}
