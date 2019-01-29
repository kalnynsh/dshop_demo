<?php

namespace shop\forms\Shop\Search;

use shop\helpers\CategoriesHelper;
use shop\helpers\BrandsHelper;
use shop\forms\Shop\Search\ValueForm;
use shop\forms\CompositeForm;
use shop\entities\Shop\Characteristic;

/**
 * SearchForm class
 *
 * @property ValueForm[] $values
 */
class SearchForm extends CompositeForm
{
    public $text;
    public $category;
    public $brand;

    public function __construct(array $config = [])
    {
        $this->values = array_map(function (Characteristic $characteristic) {
            return new ValueForm($characteristic);
        }, Characteristic::find()->orderBy('sort')->all());

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            ['text', 'string'],
            [['category', 'brand'], 'integer'],
        ];
    }

    public function categoriesList()
    {
        return CategoriesHelper::list();
    }

    public function brandsList()
    {
        return BrandsHelper::list();
    }

    public function formName(): string
    {
        return '';
    }

    protected function internalForms(): array
    {
        return ['values'];
    }
}
