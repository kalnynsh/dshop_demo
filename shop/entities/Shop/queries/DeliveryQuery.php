<?php

namespace shop\entities\Shop\queries;

use yii\db\ActiveQuery;

class DeliveryQuery extends ActiveQuery
{
    public function availableForWeight($weight)
    {
        return $this->andWhere(['and',
            ['or', ['min_weight' => null], ['<=', 'min_weight', $weight]],
            ['or', ['max_weight' => null], ['>=', 'max_weight', $weight]],
        ]);
    }

    public function availableForDistance($distance)
    {
        return $this->andWhere(['<=', 'distance', $distance]);
    }
}
