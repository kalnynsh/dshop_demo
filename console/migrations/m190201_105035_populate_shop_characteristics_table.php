<?php

use yii\db\Migration;

/**
 * Class m190201_105035_populate_shop_characteristics_table
 */
class m190201_105035_populate_shop_characteristics_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute(
            'INSERT INTO {{%shop_characteristics}} VALUES (1,\'Memory\',\'string\',0,\'8GB\',\'[\"2GB\",\"8GB\",\"8_GB_LPDDR3_SDRAM\",\"12GB\",\"16GB\",\"32GB\",\"64GB\",\"128GB\",\"256GB\"]\',1),(2,\'Display\',\'string\',0,\'\',\'[\"5.5-inch\",\"13.3-inch\",\"14-inch\",\"15.6-inch\",\"21.5-inch\",\"23.8-inch\",\"27-inch\"]\',2),(3,\'Processor\',\'string\',0,\'\',\'[\"1.80ГГц_Intel_Core_i7_8550U_(Kaby_Lake_R,_8Мб_SmartCache)\",\"8Мб_SmartCache)\",\"1.56_GHz_Octal-Core\",\"1.6_GHz_Celeron_D_Processor_360\",\"1.6_GHz_Intel_Core_i5\",\"6th_Generation_Intel_Core_i3-6100U_Processor_(3M_Cache,_2.30_GHz)\",\"3.4GHz_quad‑core_Intel_Core_i5\",\"3.5GHz_quad‑core_Intel_Core_i5\",\"3.8GHz_quad‑core_Intel_Core_i5\",\"5_GHz_Athlon_64_3500\"]\',3),(4,\'Hard Drive (Storage)\',\'string\',0,\'\',\'[\"16_GB_SSD\",\"256_GB_Flash_Memory_Solid_State\",\"500GB_5400_rpm_Hard_Drive\",\"1TB\",\"1000_Гб_+_128_Гб_SSD\",\"1TB_Fusion_Drive\",\"2TB_Fusion_Drive\"]\',4),(5,\'Video Graphics\',\'string\',0,\'\',\'[\"HD_Graphics_400\",\"Intel_HD_Graphics\",\"Intel_UHD_Graphics_620\",\"Дискретная_NVIDIA_GeForce_MX130_4_Гб\",\"Radeon_Pro_570_with_4GB_of_VRAM\",\"Radeon_Pro_575_with_4GB_of_VRAM\",\"Radeon_Pro_580_with_8GB_of_VRAM\"]\',5),(6,\'Video Support and Camera\',\'string\',0,\'\',\'[\"FaceTime_HD_camera\"]\',6),(7,\'Audio\',\'string\',0,\'Stereo speakers\',\'[\"Stereo_speakers\",\"Bang_and_Olufsen\"]\',7),(8,\'Microphone\',\'string\',0,\'3.5 mm headphone jack\',\'[\"3.5_mm_headphone_jack\"]\',8),(9,\'Operating System\',\'string\',0,\'Windows 10\',\'[\"Windows_10\",\"Windows_10_Home\",\"Chrome_OS\",\"Android\",\"Android_8.0_Oreo\",\"Microsoft_Windows_10_Домашняя,_разрядность_ОС_64-bit\"]\',9),(10,\'Multimedia Drive\',\'string\',0,\'\',\'[\"None\",\"Tray_load_DVD_Drive_(Reads_and_Writes_to_DVD/CD)\",\"Отсутствуют\"]\',10),(11,\'Media Card Reader\',\'string\',0,\'\',\'[\"4-in-1_Media_Card_Reader_and_USB_3.0\",\"SD/SDHC/SDXC\"]\',11),(12,\'Wireless Connectivity\',\'string\',0,\'\',\'[\"802.11ac_+_Bluetooth_4.0,_Dual_Band_2.4&5_GHz,_1x1\",\"Wi-Fi_(b/g/n/ac)\\\\n_LTE_не_поддерживается\\\\n_Bluetooth:_версия_4.2\"]\',12),(13,\'Miscellaneous\',\'string\',0,\'\',\'[\"4G_LTE;_Wi-Fi_Capable;_Bluetooth_4.2_wireless_technology;_MP3_Player\",\"Bluetooth:_Bluetooth_tied_to_wireless_card;_HDMI:_Yes;_Audio:_Maxx_Audio_PRO_with_Stereo_Speakers;_Accessories:_Dell_KM632_Wireless_Keyboard_&_Mouse\"]\',13),(14,\'Chipset Brand\',\'string\',0,\'Intel\',\'[\"Intel\",\"Intel_SoC_(объединен_с_процессором)\"]\',14),(15,\'Wireless Type\',\'string\',0,\'\',\'[\"802.11bgn,_Bluetooth\",\"Wi-Fi_(b/g/n/ac)\"]\',15),(16,\'Average Battery Life (in hours)\',\'string\',0,\'\',\'[\"7_hours\",\"До_9_ч.\"]\',16),(17,\'Screen Resolution\',\'string\',0,\'\',\'[\"1366_x_768\",\"1920_x_1080\",\"1920_х_1080_(Full_HD)_IPS\"]\',17),(18,\'Video Card Description\',\'string\',0,\'\',\'[\"Integrated\"]\',18),(19,\'Number of USB 3.0 Ports\',\'integer\',0,\'\',\'[\"3\"]\',19),(20,\'Color\',\'string\',0,\'\',\'[\"Gold\",\"Black_and_silver\",\"Black\",\"Multicolor\",\"Red\",\"Blue\",\"Gray_metallic\",\"Серебристый\"]\',20),(21,\'Video Display Ports\',\'string\',0,\'\',\'[\"1_x_HDMI_&_1_x_VGA\",\"1_x_HDMI\"]\',21),(22,\'Camera\',\'string\',0,\'\',\'[\"13_MP_Front_Facing_Camera\",\"HD\"]\',22),(23,\'Capacity\',\'string\',0,\'\',\'[\"1TB\",\"2TB\",\"3TB\",\"4TB\"]\',23),(24,\'Style\',\'string\',0,\'\',\'[\"Portable\"]\',24);'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%shop_characteristics}}');
    }
}
