<?php

use yii\db\Migration;

/**
 * Class m190128_145946_alter_column_user_id_in_auth_assignments_table
 */
class m190128_145946_alter_column_user_id_in_auth_assignments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn(
            '{{%auth_assignments}}',
            'user_id',
            $this->integer()->notNull()
        );

        $this->createIndex(
            '{{%idx_auth_assignments_user_id}}',
            '{{%auth_assignments}}',
            'user_id'
        );

        $this->addForeignKey(
            '{{%fk_auth_assignments_user_id}}',
            '{{%auth_assignments}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            '{{%fk_auth_assignments_user_id}}',
            '{{%auth_assignments}}'
        );

        $this->dropIndex(
            '{{%idx_auth_assignments_user_id}}',
            '{{%auth_assignments}}'
        );

        $this->alterColumn(
            '{{%auth_assignments}}',
            'user_id',
            $this->string(64)->notNull()
        );
    }
}
