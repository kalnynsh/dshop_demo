<?php

use yii\db\Migration;

/**
 * Class m190121_140613_create_table_blog_posts
 */
class m190121_140613_create_table_blog_posts extends Migration
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

        $this->createTable('{{%blog_posts}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'description' => $this->text(),
            'content' => 'MEDIUMTEXT',
            'photo' => $this->string(),
            'status' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'meta_json' => 'JSON NOT NULL',
        ], $tableOptions);

        $this->createIndex(
            '{{%idx_blog_posts_category_id}}',
            '{{%blog_posts}}',
            'category_id'
        );

        $this->addForeignKey(
            'fk_blog_posts_category_id',
            '{{%blog_posts}}',
            'category_id',
            '{{%blog_categories}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%blog_posts}}');
    }
}
