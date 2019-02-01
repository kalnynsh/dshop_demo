<?php

use yii\db\Migration;

/**
 * Class m190201_103319_populate_shop_brands_table
 */
class m190201_103319_populate_shop_brands_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute(
            'INSERT INTO {{%shop_brands}} VALUES (1,\'Apple\',\'apple\',\'{\"title\":\"Apple\",\"description\":\"Apple\",\"keywords\":\"Apple\"}\'),(2,\'Dell\',\'dell\',\'{\"title\":\"Dell\",\"description\":\"Dell\",\"keywords\":\"dell\"}\'),(3,\'HP\',\'hp\',\'{\"title\":\"HP\",\"description\":\"Hewlett Packard Enterprise\",\"keywords\":\"Hewlett Packard HP hp\"}\'),(4,\'Acer\',\'acer\',\'{\"title\":\"Acer\",\"description\":\"Acer\",\"keywords\":\"Acer acer\"}\'),(5,\'ASUS\',\'asus\',\'{\"title\":\"ASUS\",\"description\":\"ASUS\",\"keywords\":\"ASUS asus\"}\'),(6,\'Samsung\',\'samsung\',\'{\"title\":\"Samsung\",\"description\":\"Samsung\",\"keywords\":\"Samsung samsung\"}\'),(7,\'Sony\',\'sony\',\'{\"title\":\"Sony\",\"description\":\"Sony\",\"keywords\":\"Sony\"}\'),(8,\'Valoin\',\'valoin\',\'{\"title\":\"Valoin\",\"description\":\"Valoin\",\"keywords\":\"Valoin\"}\'),(9,\'Western Digital\',\'western-digital\',\'{\"title\":\"Western Digital\",\"description\":\"Western Digital\",\"keywords\":\"Western Digital\"}\'),(10,\'Seagate\',\'seagate\',\'{\"title\":\"Seagate\",\"description\":\"Seagate\",\"keywords\":\"Seagate\"}\'),(11,\'Nikon\',\'nikon\',\'{\"title\":\"Nikon\",\"description\":\"Nikon\",\"keywords\":\"Nikon\"}\');'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%shop_brands}}');
    }
}
