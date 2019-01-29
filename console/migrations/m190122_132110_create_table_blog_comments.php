<?php

use yii\db\Migration;

/**
 * Class m190122_132110_create_table_blog_comments
 */
class m190122_132110_create_table_blog_comments extends Migration
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

        $this->createTable('{{%blog_comments}}', [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'parent_id' => $this->integer(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'text' => $this->text()->notNull(),
            'active' => $this->boolean()->notNull(),
        ], $tableOptions);

        $this->createIndex(
            '{{%idx_blog_comments_post_id}}',
            '{{%blog_comments}}',
            'post_id'
        );

        $this->createIndex(
            '{{%idx_blog_comments_user_id}}',
            '{{%blog_comments}}',
            'user_id'
        );

        $this->createIndex(
            '{{%idx_blog_comments_parent_id}}',
            '{{%blog_comments}}',
            'parent_id'
        );

        $this->addForeignKey(
            'fk_blog_comments_post_id',
            '{{%blog_comments}}',
            'post_id',
            '{{%blog_posts}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_blog_comments_user_id',
            '{{%blog_comments}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_blog_comments_parent_id',
            '{{%blog_comments}}',
            'parent_id',
            '{{%blog_comments}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%blog_comments}}');
    }
}
