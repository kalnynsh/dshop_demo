<?php

use yii\db\Migration;

/**
 * Class m190201_104728_populate_shop_category_assignments
 */
class m190201_104728_populate_shop_category_assignments extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute(
            'INSERT INTO {{%shop_category_assignments}} VALUES (2,2),(3,2),(3,4),(4,6),(5,4),(5,5),(6,4),(7,4),(8,4),(9,8),(9,11),(10,12),(11,14),(12,16),(12,17),(13,16),(13,17),(14,8),(14,11),(15,4),(15,5);'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%shop_category_assignments}}');
    }
}
