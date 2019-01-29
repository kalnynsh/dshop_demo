<?php

use yii\db\Migration;

/**
 * Class m181213_091456_create_table_user_wishlist_items
 */
class m181213_091456_create_table_user_wishlist_items extends Migration
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

        $this->createTable('{{%user_wishlist_items}}', [
            'user_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey(
            '{{%pk_user_wishlist_items}}',
            '{{%user_wishlist_items}}',
            ['user_id', 'product_id']
        );

        $this->createIndex(
            '{{%idx_user_wishlist_items_user_id}}',
            '{{%user_wishlist_items}}',
            'user_id'
        );

        $this->createIndex(
            '{{%idx_user_wishlist_items_product_id}}',
            '{{%user_wishlist_items}}',
            'product_id'
        );

        $this->addForeignKey(
            '{{%fk_user_wishlist_items_user_id}}',
            '{{%user_wishlist_items}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->addForeignKey(
            '{{%fk_user_wishlist_items_product_id}}',
            '{{%user_wishlist_items}}',
            'product_id',
            '{{%shop_products}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_wishlist_items}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181213_091456_create_table_user_wishlist_items cannot be reverted.\n";

        return false;
    }
    */
}
