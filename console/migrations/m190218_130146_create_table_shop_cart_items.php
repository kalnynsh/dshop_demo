<?php

use yii\db\Migration;

/**
 * Class m190218_130146_create_table_shop_cart_items
 */
class m190218_130146_create_table_shop_cart_items extends Migration
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

        $this->createTable('{{%shop_cart_items}}', [
            'id' => $this->bigPrimaryKey(),
            'user_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'modification_id' => $this->integer(),
            'quantity' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex(
            '{{%idx_shop_cart_items_user_id}}',
            '{{%shop_cart_items}}',
            'user_id'
        );

        $this->createIndex(
            '{{%idx_shop_cart_items_product_id}}',
            '{{%shop_cart_items}}',
            'product_id'
        );

        $this->createIndex(
            '{{%idx_shop_cart_items_modification_id}}',
            '{{%shop_cart_items}}',
            'modification_id'
        );

        $this->addForeignKey(
            '{{%fk_shop_cart_items_user_id}}',
            '{{%shop_cart_items}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk_shop_cart_items_product_id}}',
            '{{%shop_cart_items}}',
            'product_id',
            '{{%shop_products}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk_shop_cart_items_modification_id}}',
            '{{%shop_cart_items}}',
            'modification_id',
            '{{%shop_modifications}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%shop_cart_items}}');
    }
}
