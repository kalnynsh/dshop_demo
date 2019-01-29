<?php

use yii\db\Migration;

/**
 * Class m181019_173615_create_table_shop_category_assignments
 */
class m181019_173615_create_table_shop_category_assignments extends Migration
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

        $this->createTable('{{%shop_category_assignments}}', [
            'product_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey(
            '{{%pk_shop_category_assignments}}',
            '{{%shop_category_assignments}}',
            [
                'product_id',
                'category_id',
            ]
        );

        $this->createIndex(
            '{{%idx_shop_category_assignments_product_id}}',
            '{{%shop_category_assignments}}',
            'product_id'
        );

        $this->createIndex(
            '{{%idx_shop_category_assignments_category_id}}',
            '{{%shop_category_assignments}}',
            'category_id'
        );

        $this->addForeignKey(
            '{{%fk_shop_category_assignments_product_id}}',
            '{{%shop_category_assignments}}',
            'product_id',
            '{{%shop_products}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->addForeignKey(
            '{{%fk_shop_category_assignments_category_id}}',
            '{{%shop_category_assignments}}',
            'category_id',
            '{{%shop_categories}}',
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
            '{{%fk_shop_category_assignments_category_id}}',
            '{{%shop_category_assignments}}'
        );
        $this->dropForeignKey(
            '{{%fk_shop_category_assignments_product_id}}',
            '{{%shop_category_assignments}}'
        );

        $this->dropIndex(
            '{{%idx_shop_category_assignments_category_id}}',
            '{{%shop_category_assignments}}'
        );

        $this->dropIndex(
            '{{%idx_shop_category_assignments_product_id}}',
            '{{%shop_category_assignments}}'
        );

        $this->dropTable('{{%shop_category_assignments}}');
    }
}
