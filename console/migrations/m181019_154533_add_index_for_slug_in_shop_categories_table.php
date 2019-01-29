<?php

use yii\db\Migration;

/**
 * Class m181019_154533_add_index_for_slug_in_shop_categories_table
 */
class m181019_154533_add_index_for_slug_in_shop_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('{{%idx_shop_categories_slug}}', '{{%shop_categories}}', 'slug', true);

        $this->insert('{{%shop_categories}}', [
            'id' => null,
            'name' => 'root',
            'slug' => 'root',
            'title' => 'root',
            'description' => 'root',
            'meta_json' => '{}',
            'lft' => 1,
            'rgt' => 2,
            'depth' => 0,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('{{%idx_shop_categories_slug}}', '{{%shop_categories}}');
    }
}
