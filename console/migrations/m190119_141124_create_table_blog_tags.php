<?php

use yii\db\Migration;

/**
 * Class m190119_141124_create_table_blog_tags
 */
class m190119_141124_create_table_blog_tags extends Migration
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

        $this->createTable('{{%blog_tags}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull()
        ], $tableOptions);

        $this->createIndex(
            '{{%idx_blog_tags}}',
            '{{%blog_tags}}',
            'slug',
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%blog_tags}}');
    }
}
