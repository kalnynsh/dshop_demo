<?php

use yii\db\Migration;

/**
 * Handles the index of table `index_for_slug_in_shop_tags`.
 */
class m181019_153133_add_index_for_slug_in_shop_tags_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('{{%idx_shop_tags_slug}}', '{{%shop_tags}}', 'slug', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('{{%idx_shop_tags_slug}}', '{{%shop_tags}}');
    }
}
