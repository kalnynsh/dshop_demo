<?php

use yii\db\Migration;

/**
 * Class m180929_076409_change_user_name_email_requirements_in_users_table
 */
class m180929_076409_change_user_name_email_requirements_in_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%users}}', '[[username]]', $this->string());
        $this->alterColumn('{{%users}}', '[[password_hash]]', $this->string());
        $this->alterColumn('{{%users}}', '[[email]]', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%users}}', '[[username]]', $this->string()->notNull());
        $this->alterColumn('{{%users}}', '[[password_hash]]', $this->string()->notNull());
        $this->alterColumn('{{%users}}', '[[email]]', $this->string()->notNull());
    }
}
