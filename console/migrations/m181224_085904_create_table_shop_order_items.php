<?php

use yii\db\Migration;

/**
 * Class m181224_085904_create_table_shop_order_items
 */
class m181224_085904_create_table_shop_order_items extends Migration
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

        $this->createTable('{{%shop_order_items}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'product_id' => $this->integer(),
            'product_name' => $this->string()->notNull(),
            'product_code' => $this->string()->notNull(),
            'modification_id' => $this->integer(),
            'modification_name' => $this->string(),
            'modification_code' => $this->string(),
            'price' => $this->integer()->notNull(),
            'quantity' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex(
            '{{%idx_shop_order_items__order_id}}',
            '{{%shop_order_items}}',
            'order_id'
        );

        $this->createIndex(
            '{{%idx_shop_order_items__product_id}}',
            '{{%shop_order_items}}',
            'product_id'
        );

        $this->createIndex(
            '{{%idx_shop_order_items__modification_id}}',
            '{{%shop_order_items}}',
            'modification_id'
        );

        $this->addForeignKey(
            '{{%fk_shop_order_items__order_id}}',
            '{{%shop_order_items}}',
            'order_id',
            '{{%shop_orders}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk_shop_order_items__product_id}}',
            '{{%shop_order_items}}',
            'product_id',
            '{{%shop_products}}',
            'id',
            'SET NULL'
        );

        $this->addForeignKey(
            '{{%fk_shop_order_items__modification_id}}',
            '{{%shop_order_items}}',
            'modification_id',
            '{{%shop_modifications}}',
            'id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%shop_order_items}}');
    }
}
