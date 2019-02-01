<?php

use yii\db\Migration;

/**
 * Class m190201_095133_populate_users_table
 */
class m190201_095133_populate_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert(
            '{{%users}}',
            [
                'id',
                'username',
                'auth_key',
                'password_hash',
                'password_reset_token',
                'email',
                'email_confirm_token',
                'status',
                'created_at',
                'updated_at'
            ],
            [
                [
                    1,
                    'admin',
                    'raw-w6G9Ff8NBSzFdTXUCWcIJbqr_p8F',
                    '$2y$13$Bqt0s8WG34LoN4P2ljzKGO5a.dWVFSTw6JSsihHBLAVAiyNo6bCSa',
                    null,
                    'admin@example.com',
                    null,
                    10,
                    \time(),
                    \time(),
                ],
                [
                    2,
                    'john',
                    'w95CUxsGr7sItQcYsuG2v_xxD4dR4dg1',
                    '$2y$13$9q/FYN8Fi3omUjj0jhGWh.pxDOdm/2I9yvuj8bmsUHI5xyJhOc3ua',
                    null,
                    'john@example.com',
                    null,
                    10,
                    \time(),
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
            '{{%users}}',
            [
                'username' => ['admin', 'john'],
            ]
        );
    }
}
