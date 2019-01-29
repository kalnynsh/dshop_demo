<?php

use yii\db\Migration;

/**
 * Class m181019_172256_add_column_main_photo_to_shop_products
 */
class m181019_172256_add_column_main_photo_to_shop_products extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%shop_products}}', 'main_photo_id', $this->integer());

        $this->createIndex(
            '{{%idx_shop_products_main_photo_id}}',
            '{{%shop_products}}',
            'main_photo_id'
        );

        $this->addForeignKey(
            '{{%fk_shop_products_main_photo_id}}',
            '{{%shop_products}}',
            'main_photo_id',
            '{{%shop_photos}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk_shop_products_main_photo_id}}', '{{%shop_products}}');
        $this->dropIndex('{{%idx_shop_products_main_photo_id}}', '{{%shop_products}}');
        $this->dropColumn('{{%shop_products}}', 'main_photo_id');
    }
}
