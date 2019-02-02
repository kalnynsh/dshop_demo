<?php

use yii\db\Migration;

/**
 * Class m181224_081931_create_table_shop_orders
 */
class m181224_081931_create_table_shop_orders extends Migration
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

        $this->createTable('{{%shop_orders}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'delivery_id' => $this->integer(),
            'delivery_name' => $this->string()->notNull(),
            'delivery_cost' => $this->integer()->notNull(),
            'payment_method' => $this->string(),
            'cost' => $this->integer()->notNull(),
            'note' => $this->text(),
            'current_status' => $this->integer()->notNull(),
            'cancel_reason' => $this->text(),
            'statuses_json' => 'JSON not null',
            'customer_name' => $this->string(),
            'customer_phone' => $this->string(),
            'delivery_index' => $this->string(),
            'delivery_address' => $this->text(),
        ], $tableOptions);

        $this->createIndex(
            '{{%idx_shop_orders__user_id}}',
            '{{%shop_orders}}',
            'user_id'
        );

        $this->createIndex(
            '{{%idx_shop_orders__delivery_id}}',
            '{{%shop_orders}}',
            'delivery_id'
        );

        $this->addForeignKey(
            '{{%fk_shop_orders__user_id}}',
            '{{%shop_orders}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk_shop_orders__delivery_id}}',
            '{{%shop_orders}}',
            'delivery_id',
            '{{%shop_deliveries}}',
            'id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%shop_orders}}');
    }
}
