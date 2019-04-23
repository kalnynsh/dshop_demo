<?php

use yii\db\Migration;

/**
 * Class m190423_120710_add_user_phone_field_to_users_table
 */
class m190423_120710_add_user_phone_field_to_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%users}}', 'phone', $this->string()->notNull());

        $this->createIndex('{{%idx_users_phone}}', '{{%users}}', 'phone', true);

        $this->batchInsert(
            '{{%users}}',
            [
                'phone',
            ],
            [
                ['79036412367'],
                ['79036902347'],
                ['79264902347'],
                ['79061907340'],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%users}}', ['phone' => '']);
        $this->dropIndex('{{%idx_users_phone}}', '{{%users}}');
        $this->dropColumn('{{%users}}', 'phone');
    }
}
