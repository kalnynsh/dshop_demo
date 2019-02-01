<?php

use yii\db\Migration;

/**
 * Class m190201_135020_populate_shop_tag_assignments_table
 */
class m190201_135020_populate_shop_tag_assignments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute(
            'INSERT INTO {{%shop_tag_assignments}} VALUES (2,1),(2,2),(3,1),(3,2),(4,3),(5,3),(5,4),(6,5),(7,5),(8,1),(9,6),(9,7),(9,9),(10,10),(11,11),(12,12),(13,12),(14,6),(14,9),(15,3);'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%shop_tag_assignments}}');
    }
}
