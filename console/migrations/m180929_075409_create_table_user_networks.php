<?php

use yii\db\Migration;

/**
 * Class m180929_075409_create_table_user_networks
 */
class m180929_075409_create_table_user_networks extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_networks}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'identity' => $this->string()->notNull(),
            'network' => $this->string(32)->notNull(),
        ], $tableOptions);

        $this->createIndex(
            '{{%idx-user_networks-identity-name}}',
            '{{%user_networks}}',
            ['identity', 'network'],
            true
        );
        $this->createIndex(
            '{{%idx-user_networks-user_id}}',
            '{{%user_networks}}',
            'user_id'
        );
        $this->addForeignKey(
            '{{%fk-user_networks-user_id}}',
            '{{%user_networks}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%user_networks}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180929_075409_create_table_user_networks cannot be reverted.\n";

        return false;
    }
    */
}
