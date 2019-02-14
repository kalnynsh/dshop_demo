<?php

use yii\db\Migration;

/**
 * Class m181022_114149_alter_column_description_to_text_in_shop_categories_table
 */
class m181022_114149_alter_column_description_to_text_in_shop_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%shop_categories}}', '[[description]]', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%shop_categories}}', '[[description]]', $this->string());
    }
}
