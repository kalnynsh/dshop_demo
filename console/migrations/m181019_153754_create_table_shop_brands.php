<?php

use yii\db\Migration;

/**
 * Class m181019_153754_create_table_shop_brands
 */
class m181019_153754_create_table_shop_brands extends Migration
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

        $this->createTable('{{%shop_brands}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
            'meta_json' => $this->text()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx_shop_brands_slug}}', '{{%shop_brands}}', 'slug', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('{{%idx_shop_brands_slug}}', '{{%shop_brands}}');
        $this->dropTable('{{%shop_brands}}');
    }
}
