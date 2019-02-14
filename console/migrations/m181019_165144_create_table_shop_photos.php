<?php

use yii\db\Migration;

/**
 * Class m181019_161859_create_table_shop_photos
 */
class m181019_165144_create_table_shop_photos extends Migration
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

        $this->createTable('{{%shop_photos}}', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()->notNull(),
            'file' => $this->string()->notNull(),
            'sort' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex(
            '{{%idx_shop_photos_product_id}}',
            '{{%shop_photos}}',
            'product_id'
        );

        $this->addForeignKey(
            '{{%fk_shop_photos_product_id}}',
            '{{%shop_photos}}',
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
        $this->dropForeignKey('{{%fk_shop_photos_product_id}}', '{{%shop_photos}}');
        $this->dropIndex('{{%idx_shop_photos_product_id}}', '{{%shop_photos}}');
        $this->dropTable('{{%shop_photos}}');
    }
}
