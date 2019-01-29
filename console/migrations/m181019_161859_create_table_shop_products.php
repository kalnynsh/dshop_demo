<?php

use yii\db\Migration;

/**
 * Class m181019_165144_create_table_shop_products
 */
class m181019_161859_create_table_shop_products extends Migration
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

        $this->createTable('{{%shop_products}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'brand_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'code' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'description' => $this->text(),
            'weight' => $this->integer(),
            'quantity' => $this->integer()->notNull(),
            'status' => $this->smallInteger()->notNull(),
            'price_old' => $this->integer(),
            'price_new' => $this->integer(),
            'rating' => $this->decimal(3, 2),
            'meta_json' => $this->text(),
        ], $tableOptions);

        $this->createIndex('{{%idx_shop_products_code}}', '{{%shop_products}}', 'code', true);
        $this->createIndex(
            '{{%idx_shop_products_category_id}}',
            '{{%shop_products}}',
            'category_id'
        );
        $this->createIndex(
            '{{%idx_shop_products_brand_id}}',
            '{{%shop_products}}',
            'brand_id'
        );

        $this->addForeignKey(
            '{{%fk_shop_products_category_id}}',
            '{{%shop_products}}',
            'category_id',
            '{{%shop_categories}}',
            'id'
        );

        $this->addForeignKey(
            '{{%fk_shop_products_brand_id}}',
            '{{%shop_products}}',
            'brand_id',
            '{{%shop_brands}}',
            'id'
        );
    }

/**
 * {@inheritdoc}
 */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk_shop_products_brand_id}}', '{{%shop_products}}');
        $this->dropForeignKey('{{%fk_shop_products_category_id}}', '{{%shop_products}}');

        $this->dropIndex('{{%idx_shop_products_brand_id}}', '{{%shop_products}}');
        $this->dropIndex('{{%idx_shop_products_category_id}}', '{{%shop_products}}');

        $this->dropTable('{{%shop_products}}');
    }
}
