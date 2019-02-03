<?php

use yii\db\Migration;

/**
 * Class m190201_134204_populate_shop_discounts__table
 */
class m190201_134204_populate_shop_discounts__table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute(
            'INSERT INTO {{%shop_discounts}}
            VALUES
            (1,5,\'New Year discount, 5%\',\'2018-12-01\',\'2019-01-01\',1,1),
            (2,7,\'Birthday coupon, discount 7%\',\'2018-12-17\',\'2018-12-17\',1,2);'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%shop_discounts}}');
    }
}
