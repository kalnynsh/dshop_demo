<?php

use yii\db\Migration;

/**
 * Class m181019_182951_create_table_shop_values
 */
class m181019_182951_create_table_shop_values extends Migration
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

        $this->createTable('{{%shop_values}}', [
            'product_id' => $this->integer()->notNull(),
            'characteristic_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey(
            '{{%pk_shop_values}}',
            '{{%shop_values}}',
            [
                'product_id',
                'characteristic_id',
            ]
        );

        $this->createIndex(
            '{{%idx_shop_values_product_id}}',
            '{{%shop_values}}',
            'product_id'
        );

        $this->createIndex(
            '{{%idx_shop_values_characteristic_id}}',
            '{{%shop_values}}',
            'characteristic_id'
        );

        $this->addForeignKey(
            '{{%fk_shop_values_product_id}}',
            '{{%shop_values}}',
            'product_id',
            '{{%shop_products}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->addForeignKey(
            '{{%fk_shop_values_characteristic_id}}',
            '{{%shop_values}}',
            'characteristic_id',
            '{{%shop_characteristics}}',
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
            '{{%fk_shop_values_characteristic_id}}',
            '{{%shop_values}}'
        );
        $this->dropForeignKey(
            '{{%fk_shop_values_product_id}}',
            '{{%shop_values}}'
        );

        $this->dropIndex(
            '{{%idx_shop_values_characteristic_id}}',
            '{{%shop_values}}'
        );

        $this->dropIndex(
            '{{%idx_shop_values_product_id}}',
            '{{%shop_values}}'
        );

        $this->dropTable('{{%shop_values}}');
    }
}
