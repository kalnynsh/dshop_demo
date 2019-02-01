<?php

use yii\db\Migration;

/**
 * Class m190201_134518_populate_shop_modifications_table
 */
class m190201_134518_populate_shop_modifications_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute(
            'INSERT INTO {{%shop_modifications}} VALUES (1,2,\'MNE91RU/A\',\'Apple iMac 21.5 Retina 5K Core i5 3.4 GHz, 8 ГБ, 1 TB Fusion Drive, Radeon Pro 570 4 GB\',2107,350),(2,13,\'STDR2000102\',\'Seagate Backup Plus Slim 2TB Portable Hard Drive External USB 3.0, Blue + 2mo Adobe CC Photography \',86,95),(3,13,\'STDR2000103\',\'Seagate Backup Plus Slim 2TB Portable Hard Drive External USB 3.0, Red + 2mo Adobe CC Photography \',86,38);'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%shop_modifications}}');
    }
}
