<?php

use yii\db\Migration;

/**
 * Class m181019_190015_create_table_shop_modifications
 */
class m181019_190015_create_table_shop_modifications extends Migration
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

        $this->createTable('{{%shop_modifications}}', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()->notNull(),
            'code' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'price' => $this->integer(),
        ], $tableOptions);

        $this->createIndex(
            '{{%idx_shop_modifications_code}}',
            '{{%shop_modifications}}',
            'code'
        );

        $this->createIndex(
            '{{%idx_shop_modifications_product_id}}',
            '{{%shop_modifications}}',
            'product_id'
        );

        $this->createIndex(
            '{{%idx_shop_modifications_product_id__code}}',
            '{{%shop_modifications}}',
            [
                'product_id',
                'code',
            ],
            true
        );

        $this->addForeignKey(
            '{{%fk_shop_modifications_product_id}}',
            '{{%shop_modifications}}',
            'product_id',
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
        $this->dropTable('{{%shop_modifications}}');
    }
}
