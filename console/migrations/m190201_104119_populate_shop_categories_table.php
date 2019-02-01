<?php

use yii\db\Migration;

/**
 * Class m190201_104119_populate_shop_categories_table
 */
class m190201_104119_populate_shop_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute(
            'INSERT INTO {{%shop_categories}} VALUES (1,\'root\',\'root\',\'root\',\'root\',\'{}\',1,42,0),(2,\'Компьютеры\',\'desktop-computers\',\'Компьютеры\',NULL,\'{\"title\":\"Desktop Computers\",\"description\":\"Desktop Computers\",\"keywords\":\"desktops\"}\',13,14,2),(3,\'Моноблоки\',\'all-in-one-desktops\',\'Моноблоки\',\'All-in-One Desktops (Monoblocks)\',\'{\"title\":\"All-in-One Desktops\",\"description\":\"All-in-One Desktops (Monoblocks)\",\"keywords\":\"all-in-one desktops monoblocks\"}\',11,12,2),(4,\'Компьютеры и периферия\',\'computers-and-peripherals\',\'Компьютеры и периферия\',NULL,\'{\"title\":\"Computers & Peripherals\",\"description\":\"Computers and Peripherals\",\"keywords\":\"Computers and Peripherals\"}\',2,15,1),(5,\'Ноутбуки и аксессуары\',\'laptops\',\'Ноутбуки и аксессуары\',NULL,\'{\"title\":\"Laptops\",\"description\":\"Laptops\",\"keywords\":\"Laptops\"}\',3,8,2),(6,\'Ноутбуки\',\'traditional-laptops\',\'Ноутбуки\',NULL,\'{\"title\":\"Traditional Laptops\",\"description\":\"Traditional Laptops\",\"keywords\":\"Traditional Laptops\"}\',4,5,3),(7,\'Мониторы и аксессуары\',\'monitors\',\'Мониторы и аксессуары\',NULL,\'{\"title\":\"Monitors and accessories\",\"description\":\"Monitors and accessories\",\"keywords\":\"Monitors, accessories\"}\',9,10,2),(8,\'Фото- видеокамеры и аксессуары\',\'cameras-photo-video\',\'Фото- видеокамеры и аксессуары\',NULL,\'{\"title\":\"Cameras: Photo, Video\",\"description\":\"Cameras: Photo, Video\",\"keywords\":\"Cameras, Photo, Video\"}\',16,23,1),(9,\'Компактные фотоаппараты Sony\',\'mirrorless-cameras-sony\',\'Компактные фотоаппараты Sony\',NULL,\'{\"title\":\"Mirrorless Cameras Sony\",\"description\":\"Mirrorless Cameras Sony\",\"keywords\":\"Mirrorless Cameras Sony\"}\',18,19,3),(11,\'Компактные фотоаппараты\',\'digital-cameras\',\'Компактные фотоаппараты\',NULL,\'{\"title\":\"Digital Cameras\",\"description\":\"Digital Cameras\",\"keywords\":\"Digital Cameras\"}\',17,22,2),(12,\'Смартфоны, планшеты и гаджеты\',\'smart-phones-tablets-gadgets\',\'Смартфоны, планшеты и гаджеты\',NULL,\'{\"title\":\"Smart Phones, Tablets and Gadgets\",\"description\":\"Smart Phones, Tablets and Gadgets\",\"keywords\":\"Smart Phones, Tablets and Gadgets\"}\',24,27,1),(13,\'Смартфоны и мобильные телефоны\',\'carrier-phones\',\'Смартфоны и мобильные телефоны\',NULL,\'{\"title\":\"Carrier Phones\",\"description\":\"Carrier Phones\",\"keywords\":\"Carrier Phones\"}\',25,26,2),(14,\'Портативное аудио и аксессуары\',\'portable-audio-accessories\',\'Портативное аудио и аксессуары\',NULL,\'{\"title\":\"Portable Audio & Accessories\",\"description\":\"Portable Audio & Accessories\",\"keywords\":\"Portable Audio Accessories\"}\',30,33,3),(15,\'MP3 - плееры\',\'mp3-players\',\'MP3 - плееры\',NULL,\'{\"title\":\"MP3 Players\",\"description\":\"MP3 Players\",\"keywords\":\"MP3 Players\"}\',31,32,4),(16,\'Внешние жесткие диски, карты памяти, флэшки\',\'external-components\',\'Внешние жесткие диски, карты памяти, флэшки\',NULL,\'{\"title\":\"External Components Computers\",\"description\":\"External Components for Computers and Accessories: portable HDD, memories card, USB flash drivers, CD DVD disks, cart readers\",\"keywords\":\"External Components Computers and Accessories\"}\',36,41,1),(17,\'Устройства хранения данных\',\'data-storage\',\'Устройства хранения данных\',NULL,\'{\"title\":\"Data Storage\",\"description\":\"Data Storage\",\"keywords\":\"Data Storage\"}\',37,40,2),(18,\'Внешние жесткие диски\',\'external-hard-drives\',\'Внешние жесткие диски\',NULL,\'{\"title\":\"External Hard Drives\",\"description\":\"External Hard Drives\",\"keywords\":\"External Hard Drives\"}\',38,39,3),(19,\'Аксессуары для ноутбуков\',\'laptops-accessories\',\'Аксессуары для ноутбуков\',NULL,\'{\"title\":\"Laptops & Accessories\",\"description\":\"Accessories for Laptops\",\"keywords\":\"Laptops Accessories\"}\',6,7,3),(20,\'Телевизоры, видео-аудио техника\',\'tv-video-audio\',\'Телевизоры, видео-аудио техника\',NULL,\'{\"title\":\"TV, Video, Audio\",\"description\":\"TV, Video, Audio\",\"keywords\":\"TV Video Audio\"}\',28,35,1),(21,\'Аудиотехника и аксессуары\',\'audio-accessories\',\'Аудиотехника и аксессуары\',NULL,\'{\"title\":\"Audio and accessories\",\"description\":\"Audio and accessories\",\"keywords\":\"Audio accessories\"}\',29,34,2),(22,\'Компактные фотоаппараты Nikon\',\'mirrorless-camera-nikon\',\'Компактные фотоаппараты Nikon\',NULL,\'{\"title\":\"Mirrorless cameras Nikon\",\"description\":\"Mirrorless cameras Nikon\",\"keywords\":\"mirrorless camera nikon\"}\',20,21,3);'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%shop_categories}}');
    }
}
