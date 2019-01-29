<?php

use yii\db\Migration;

/**
 * Class m181222_100505_create_table_shop_deliveries
 */
class m181222_100505_create_table_shop_deliveries extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%shop_deliveries}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'distance' => $this->integer(),
            'min_weight' => $this->integer(),
            'max_weight' => $this->integer(),
            'cost' => $this->integer()->notNull(),
            'sort' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%shop_deliveries}}');
    }
}
