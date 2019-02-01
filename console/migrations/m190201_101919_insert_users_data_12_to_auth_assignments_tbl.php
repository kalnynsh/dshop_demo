<?php

use yii\db\Migration;

/**
 * Class m190201_101919_insert_users_data_12_to_auth_assignments_tbl
 */
class m190201_101919_insert_users_data_12_to_auth_assignments_tbl extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert(
            '{{%auth_assignments}}',
            [
                'item_name',
                'user_id',
                'created_at'
            ],
            [
                [
                    'admin',
                    1,
                    \time(),
                ],
                [
                    'user',
                    2,
                    \time(),
                ],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete(
            '{{%auth_assignments}}',
            [
                'user_id' => [1, 2],
            ]
        );
    }
}
