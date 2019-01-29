<?php

use yii\db\Migration;

/**
 * Class m181019_191440_create_table_shop_reviews
 */
class m181019_191440_create_table_shop_reviews extends Migration
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

        $this->createTable('{{%shop_reviews}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'vote' => $this->integer()->notNull(),
            'text' => $this->text()->notNull(),
            'active' => $this->boolean()->notNull(),
        ], $tableOptions);

        $this->createIndex(
            '{{%idx_shop_reviews_user_id}}',
            '{{%shop_reviews}}',
            'user_id'
        );

        $this->createIndex(
            '{{%idx_shop_reviews_product_id}}',
            '{{%shop_reviews}}',
            'product_id'
        );

        $this->addForeignKey(
            '{{%fk_shop_reviews_user_id}}',
            '{{%shop_reviews}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->addForeignKey(
            '{{%fk_shop_reviews_product_id}}',
            '{{%shop_reviews}}',
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
        $this->dropTable('{{%shop_reviews}}');
    }
}
