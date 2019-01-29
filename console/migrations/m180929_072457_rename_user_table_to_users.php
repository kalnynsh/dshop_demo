<?php

use yii\db\Migration;

/**
 * Class m180929_072457_rename_user_table_to_users
 */
class m180929_072457_rename_user_table_to_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameTable('{{%user}}', '{{%users}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameTable('{{%users}}', '{{%user}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180929_072457_rename_user_table_to_users cannot be reverted.\n";

        return false;
    }
    */
}
