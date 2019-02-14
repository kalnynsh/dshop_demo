<?php

use yii\db\Migration;

/**
 * Class m180927_172035_add_column_email_reset_token_to_user_table
 */
class m180927_172035_add_column_email_reset_token_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            '{{%user}}',
            'email_confirm_token',
            $this->string()->unique()->after('email')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(
            '{{%user}}',
            'email_confirm_token'
        );
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180927_172035_add_column_email_reset_token_to_user_table cannot be reverted.\n";

        return false;
    }
    */
}
