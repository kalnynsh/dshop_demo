<?php

use yii\db\Migration;

/**
 * Class m181025_070503_add_column_value_to_shop_values_table
 */
class m181025_070503_add_column_value_to_shop_values_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%shop_values}}', '[[value]]', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%shop_values}}', '[[value]]');
    }
}
