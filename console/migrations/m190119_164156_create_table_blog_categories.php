<?php

use yii\db\Migration;

/**
 * Class m190119_164156_create_table_blog_categories
 */
class m190119_164156_create_table_blog_categories extends Migration
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

        $this->createTable('{{%blog_categories}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
            'title' => $this->string(),
            'description' => $this->text(),
            'sort' => $this->integer()->notNull(),
            'meta_json' => 'JSON NOT NULL',
        ], $tableOptions);

        $this->createIndex(
            '{{%idx_blog_categories}}',
            '{{%blog_categories}}',
            'slug',
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%blog_categories}}');
    }
}
