<?php

namespace backend\forms\Shop;

use yii\db\ActiveQuery;
use yii\data\ActiveDataProvider;
use yii\base\Model;
use shop\entities\Shop\Delivery;

class DeliverySearch extends Model
{
    public $id;
    public $name;
    public $distance;

    private $query;

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->query = Delivery::find();
    }

    public function rules(): array
    {
        return [
            [['id', 'distance'], 'integer'],
            ['name', 'safe'],
        ];
    }

    /**
     * search function
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = $this->query;
        $dataProvider = $this->getActiveDataProvider($query);
        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');

            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query
            ->andFilterWhere(['like', 'name', $this->name]);

        $query
            ->andFilterWhere(['distance' => $this->distance]);

        return $dataProvider;
    }

    private function getActiveDataProvider(ActiveQuery $query): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'sort' => SORT_ASC,
                ],
            ],
        ]);
    }
}
