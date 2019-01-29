<?php

use yii\db\Migration;

/**
 * Class m190128_151204_insert_user_roles_to_auths_tables
 */
class m190128_151204_insert_user_roles_to_auths_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert(
            '{{%auth_items}}',
            ['type', 'name', 'description'],
            [
                [
                    1, 'user', 'User',
                ],
                [
                    1, 'admin', 'Admin',
                ],
            ]
        );

        $this->batchInsert(
            '{{%auth_item_children}}',
            ['parent', 'child'],
            [
                ['admin', 'user'],
            ]
        );

        $this->execute(
            'INSERT INTO {{%auth_assignments}} (item_name, user_id)
                SELECT \'user\', u.id
                FROM {{%users}} u ORDER BY u.id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete(
            '{{%auth_items}}',
            [
                'name' => ['user', 'admin'],
            ]
        );
    }
}
