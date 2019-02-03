<?php

use yii\db\Migration;

/**
 * Class m190122_133740_add_column_comments_count_to_tbl_blog_posts
 */
class m190122_133740_add_column_comments_count_to_tbl_blog_posts extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            '{{%blog_posts}}',
            '[[comments_count]]',
            $this->integer()->notNull()
        );

        $this->update(
            '{{%blog_posts}}',
            [
                '[[comments_count]]' => 0,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%blog_posts}}', 'comments_count');
    }
}
