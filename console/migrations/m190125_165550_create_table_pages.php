<?php

use yii\db\Migration;

/**
 * Class m190125_165550_create_table_pages
 */
class m190125_165550_create_table_pages extends Migration
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

        $this->createTable('{{%pages}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
            'content' => 'MEDIUMTEXT',
            'meta_json' => $this->json()->notNull(),
            'lft' => $this->integer()->notNull(),
            'rgt' => $this->integer()->notNull(),
            'depth' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex(
            '{{%idx_pages_slug}}',
            '{{%pages}}',
            'slug',
            true
        );

        $this->insert('{{%pages}}', [
            'id' => 1,
            'title' => 'This is root',
            'slug' => 'root',
            'content' => 'This is root',
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
        $this->dropTable('{{%pages}}');
    }
}
