<?php

use yii\db\Migration;

/**
 * Class m190201_133847_populate_shop_deliveries_table
 */
class m190201_133847_populate_shop_deliveries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute(
            'INSERT INTO {{%shop_deliveries}}
            VALUES
            (1,\'Downtown delivery\',60000,1,150000,5,2),
            (2,\'Small track, 1 hour\',60000,100000,1000000,23,3),
            (3,\'Small track, 2 hour\',100000,100000,1000000,300,4),
            (4,\'Self delivery\',0,0,1000000,0,1);'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%shop_deliveries}}');
    }
}
