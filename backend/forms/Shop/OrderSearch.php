<?php

namespace backend\forms\Shop;

use yii\db\ActiveQuery;
use yii\data\ActiveDataProvider;
use yii\base\Model;
use shop\services\manage\Shop\OrderManageService;
use shop\helpers\OrderHelper;

/**
 * OrderSearch class
 *
 * @property int $id
 * @property OrderManageService $service
 */
class OrderSearch extends Model
{
    public $id;
    private $service;

    public function __construct(OrderManageService $service, $config = [])
    {
        $this->service = $service;
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['id'], 'integer'],
        ];
    }

    public function search(array $params): ActiveDataProvider
    {
        $dataProvider = $this->getActiveDataProvider([
            'query' => $this->query(),
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $this->query()->where('0=1');

            return $dataProvider;
        }

        $this->query()->andFilterWhere([
            'id' => $this->id,
        ]);

        return $dataProvider;
    }

    public function query(): ActiveQuery
    {
        return $this->service->getQuery();
    }

    private function getActiveDataProvider(array $data): ActiveDataProvider
    {
        return new ActiveDataProvider($data);
    }

    public function statusesList(): array
    {
        return OrderHelper::statusesList();
    }
}
