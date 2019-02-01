<?php

use yii\db\Migration;

/**
 * Class m190201_135019_populate_shop_tags_table
 */
class m190201_135019_populate_shop_tags_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute(
            'INSERT INTO `shop_tags` VALUES (1,\'Desktops\',\'desktops\'),(2,\'Monoblocks\',\'monoblocks\'),(3,\'Laptops\',\'laptops\'),(4,\'Traditional Laptops\',\'traditional-laptops\'),(5,\'Monitors\',\'monitors\'),(6,\'Cameras\',\'cameras\'),(7,\'Photo\',\'photo\'),(8,\'Video\',\'video\'),(9,\'Digital Cameras\',\'digital-cameras\'),(10,\'Phones\',\'phones\'),(11,\'Portable Audio\',\'portable-audio\'),(12,\'Portable External Hard Drives\',\'portable-external-hard-drives\');'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%shop_tags}}');
    }
}
