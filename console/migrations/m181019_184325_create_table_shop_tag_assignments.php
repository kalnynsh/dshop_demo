<?php

use yii\db\Migration;

/**
 * Class m181019_184325_create_table_shop_tag_assignments
 */
class m181019_184325_create_table_shop_tag_assignments extends Migration
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

        $this->createTable('{{%shop_tag_assignments}}', [
            'product_id' => $this->integer()->notNull(),
            'tag_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey(
            '{{%pk_shop_tag_assignments}}',
            '{{%shop_tag_assignments}}',
            [
                'product_id',
                'tag_id',
            ]
        );

        $this->createIndex(
            '{{%idx_shop_tag_assignments_product_id}}',
            '{{%shop_tag_assignments}}',
            'product_id'
        );

        $this->createIndex(
            '{{%idx_shop_tag_assignments_tag_id}}',
            '{{%shop_tag_assignments}}',
            'tag_id'
        );

        $this->addForeignKey(
            '{{%fk_shop_tag_assignments_product_id}}',
            '{{%shop_tag_assignments}}',
            'product_id',
            '{{%shop_products}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->addForeignKey(
            '{{%fk_shop_tag_assignments_tag_id}}',
            '{{%shop_tag_assignments}}',
            'tag_id',
            '{{%shop_tags}}',
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
        $this->dropForeignKey(
            '{{%fk_shop_tag_assignments_tag_id}}',
            '{{%shop_tag_assignments}}'
        );
        $this->dropForeignKey(
            '{{%fk_shop_tag_assignments_product_id}}',
            '{{%shop_tag_assignments}}'
        );

        $this->dropIndex(
            '{{%idx_shop_tag_assignments_tag_id}}',
            '{{%shop_tag_assignments}}'
        );

        $this->dropIndex(
            '{{%idx_shop_tag_assignments_product_id}}',
            '{{%shop_tag_assignments}}'
        );

        $this->dropTable('{{%shop_tag_assignments}}');
    }
}
