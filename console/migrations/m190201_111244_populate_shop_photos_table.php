<?php

use yii\db\Migration;

/**
 * Class m190201_111244_populate_shop_photos_table
 */
class m190201_111244_populate_shop_photos_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute(
            'INSERT INTO `shop_photos` VALUES (2,2,\'01.jpg\',0),(3,2,\'imac-2017-display_01.jpg\',4),(4,2,\'02.jpg\',1),(5,2,\'03.jpg\',2),(6,2,\'04.jpg\',3),(7,3,\'01.jpg\',0),(8,3,\'03.jpg\',1),(9,3,\'05.jpg\',2),(10,4,\'01.jpg\',0),(11,4,\'02.jpg\',1),(12,4,\'03.jpg\',2),(13,4,\'04.jpg\',3),(14,5,\'00.jpg\',0),(15,5,\'01.jpg\',1),(16,5,\'02.jpg\',2),(17,5,\'03.jpg\',3),(18,5,\'04.jpg\',4),(19,5,\'05.jpg\',5),(20,6,\'01.jpg\',0),(21,6,\'02.jpg\',1),(22,6,\'03.jpg\',2),(23,6,\'04.jpg\',3),(24,6,\'05.jpg\',4),(25,7,\'01.jpg\',0),(26,7,\'02.jpg\',1),(27,7,\'03.jpg\',2),(28,7,\'04.jpg\',3),(29,7,\'05.jpg\',4),(30,8,\'61BHsSh1kzL._SL1500_.jpg\',1),(31,8,\'61Eu0YWRXmL._SL1500_.jpg\',2),(32,8,\'61mmVQKB39L._SL1500_.jpg\',0),(33,8,\'81IHwvjZtKL._SL1500_.jpg\',3),(34,9,\'61euSP6eKQL._SL1200_.jpg\',0),(36,9,\'61KfphFCMpL._SL1200_.jpg\',1),(37,9,\'61QVFUcWnUL._SL1200_.jpg\',2),(38,9,\'61XR46aRKTL._SL1200_.jpg\',3),(39,9,\'61XTxR2xKHL._SL1200_.jpg\',4),(41,9,\'71+GfYos2pL._SL1200_.jpg\',6),(42,10,\'61agmpRhBRL._SL1500_.jpg\',3),(43,10,\'61-ZYTlIhJL._SL1500_.jpg\',4),(44,10,\'71gG4xAdfvL._SL1500_.jpg\',0),(45,10,\'71raU+DSDbL._SL1500_.jpg\',1),(46,10,\'81Uo3apzhmL._SL1500_.jpg\',5),(47,10,\'714CEzNJV4L._SL1500_.jpg\',2),(48,11,\'81-6SgUqqRL._SL1500_.jpg\',3),(49,11,\'81CYbfNkLiL._SL1500_.jpg\',4),(50,11,\'81SAMD67kIL._SL1500_.jpg\',0),(51,11,\'81t-vBrv5eL._SL1500_.jpg\',2),(52,11,\'81WGmBAhYoL._SL1500_.jpg\',5),(53,11,\'818iJGKKdAL._SL1500_.jpg\',6),(54,11,\'8102BXrYPDL._SL1500_.jpg\',1),(55,12,\'31yADVIi2xL.jpg\',0),(56,12,\'41rDz7U3DNL.jpg\',1),(57,12,\'51PPA-5WNqL._SL1000_.jpg\',2),(58,12,\'81Fc+kci1IL._SL1500_.jpg\',3),(59,13,\'51BYlb-7XVL.jpg\',5),(60,13,\'61RciIlL-GL._SL1500_.jpg\',1),(61,13,\'71aTdMstkGL._SL1500_.jpg\',2),(62,13,\'71jWfgkE+rL._SL1500_.jpg\',4),(63,13,\'81AjSHnPSnL._SL1500_.jpg\',0),(64,13,\'81R8OM9pGLL._SL1500_.jpg\',3),(65,13,\'91LcMrbtzmL._SL1500_.jpg\',6),(66,13,\'716f3AsuXSL._SL1500_.jpg\',7),(67,13,\'818Yu7-rAlL._SL1500_.jpg\',8),(68,14,\'0344015_4.jpg\',0),(69,14,\'0344015_5.jpg\',1),(70,14,\'0344015_6.jpg\',2),(71,14,\'0344015_7.jpg\',3),(72,14,\'0344015_9.jpg\',4),(73,15,\'0669261_01.jpg\',0),(74,15,\'0669261_03.jpg\',1),(75,15,\'0669261_05.jpg\',2);'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%shop_photos}}');
    }
}
