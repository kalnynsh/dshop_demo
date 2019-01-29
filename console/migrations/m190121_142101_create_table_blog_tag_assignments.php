<?php

use yii\db\Migration;

/**
 * Class m190121_142101_create_table_blog_tag_assignments
 */
class m190121_142101_create_table_blog_tag_assignments extends Migration
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

        $this->createTable('{{%blog_tag_assignments}}', [
            'post_id' => $this->integer()->notNull(),
            'tag_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey(
            '{{%pk_blog_tag_assignments}}',
            '{{%blog_tag_assignments}}',
            [
                'post_id',
                'tag_id',
            ]
        );

        $this->createIndex(
            '{{%idx_blog_tag_assignments_post_id}}',
            '{{%blog_tag_assignments}}',
            'post_id'
        );

        $this->createIndex(
            '{{%idx_blog_tag_assignments_tag_id}}',
            '{{%blog_tag_assignments}}',
            'tag_id'
        );

        $this->addForeignKey(
            '{{%fk_blog_tag_assignments_post_id}}',
            '{{%blog_tag_assignments}}',
            'post_id',
            '{{%blog_posts}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->addForeignKey(
            '{{%fk_blog_tag_assignments_tag_id}}',
            '{{%blog_tag_assignments}}',
            'tag_id',
            '{{%blog_tags}}',
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
        $this->dropTable('{{%blog_tag_assignments}}');
    }
}
