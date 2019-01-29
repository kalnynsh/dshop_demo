<?php

namespace backend\forms\Shop;

use yii\db\ActiveQuery;
use yii\data\ActiveDataProvider;
use yii\base\Model;
use shop\helpers\ProductHelper;
use shop\helpers\CategoriesHelper;
use shop\entities\Shop\Product\Product;

class ProductSearch extends Model
{
    public $id;
    public $code;
    public $name;
    public $category_id;
    public $brand_id;
    public $status;
    public $quantity;

    public function rules(): array
    {
        return [
            [
                ['id', 'category_id', 'brand_id', 'status', 'quantity'],
                'integer',
            ],
            [
                ['code', 'name'], 'safe',
            ],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = $this->getQuery()->with('mainPhoto', 'category');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
            'status' => $this->status,
            'quantity' => $this->quantity,
        ]);

        $query
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }

    public function categoriesList(): array
    {
        return CategoriesHelper::list();
    }

    public function statusList(): array
    {
        return ProductHelper::statusList();
    }

    private function getQuery(): ActiveQuery
    {
        return Product::find();
    }
}
