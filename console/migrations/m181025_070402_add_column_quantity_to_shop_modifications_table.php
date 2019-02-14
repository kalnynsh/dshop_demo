<?php

use yii\db\Migration;

/**
 * Class m181025_070402_add_column_quantity_to_shop_modifications_table
 */
class m181025_070402_add_column_quantity_to_shop_modifications_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%shop_modifications}}', '[[quantity]]', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%shop_modifications}}', '[[quantity]]');
    }
}
