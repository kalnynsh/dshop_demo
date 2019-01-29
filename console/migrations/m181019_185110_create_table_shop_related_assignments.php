<?php

use yii\db\Migration;

/**
 * Class m181019_185110_create_table_shop_related_assignments
 */
class m181019_185110_create_table_shop_related_assignments extends Migration
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

        $this->createTable('{{%shop_related_assignments}}', [
            'product_id' => $this->integer()->notNull(),
            'related_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey(
            '{{%pk_shop_related_assignments}}',
            '{{%shop_related_assignments}}',
            [
                'product_id',
                'related_id',
            ]
        );

        $this->createIndex(
            '{{%idx_shop_related_assignments_product_id}}',
            '{{%shop_related_assignments}}',
            'product_id'
        );

        $this->createIndex(
            '{{%idx_shop_related_assignments_related_id}}',
            '{{%shop_related_assignments}}',
            'related_id'
        );

        $this->addForeignKey(
            '{{%fk_shop_related_assignments_product_id}}',
            '{{%shop_related_assignments}}',
            'product_id',
            '{{%shop_products}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->addForeignKey(
            '{{%fk_shop_related_assignments_related_id}}',
            '{{%shop_related_assignments}}',
            'related_id',
            '{{%shop_products}}',
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
            '{{%fk_shop_related_assignments_related_id}}',
            '{{%shop_related_assignments}}'
        );
        $this->dropForeignKey(
            '{{%fk_shop_related_assignments_product_id}}',
            '{{%shop_related_assignments}}'
        );

        $this->dropIndex(
            '{{%idx_shop_related_assignments_related_id}}',
            '{{%shop_related_assignments}}'
        );

        $this->dropIndex(
            '{{%idx_shop_related_assignments_product_id}}',
            '{{%shop_related_assignments}}'
        );

        $this->dropTable('{{%shop_related_assignments}}');
    }
}
