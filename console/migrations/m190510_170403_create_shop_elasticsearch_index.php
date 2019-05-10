<?php

use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use yii\db\Migration;

/**
 * Class m190510_170403_create_shop_elasticsearch_index
 */
class m190510_170403_create_shop_elasticsearch_index extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $client = $this->getClient();

        try {
            $client->indices()->delete([
                'index' => 'shop',
            ]);
        } catch (Missing404Exception $error) {}

        $client->indices()->create([
            'index' => 'shop',
            'body'  => [
                'mappings' => [
                    'products' => [
                        '_source'    => [
                            'enabled' => true,
                        ],
                        'properties' => [
                            'id'          => [
                                'type' => 'integer',
                            ],
                            'name'        => [
                                'type' => 'text',
                            ],
                            'description' => [
                                'type' => 'text',
                            ],
                            'price'       => [
                                'type' => 'integer',
                            ],
                            'rating'      => [
                                'type' => 'float',
                            ],
                            'brand'       => [
                                'type' => 'integer',
                            ],
                            'categories'  => [
                                'type' => 'integer',
                            ],
                            'tags'        => [
                                'type' => 'integer',
                            ],
                            'values'      => [
                                'type'       => 'nested',
                                'properties' => [
                                    'characteristic' => [
                                        'type' => 'integer',
                                    ],
                                    'value_string'   => [
                                        'type' => 'keyword',
                                    ],
                                    'value_int'      => [
                                        'type' => 'integer',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        try {
            $this->getClient()->indices()->delete([
                'index' => 'shop',
            ]);
        } catch (Missing404Exception $error) {}
    }

    private function getClient(): Client
    {
        return Yii::$container->get(Client::class);
    }

    /*
// Use up()/down() to run migration code without a transaction.
public function up()
{

}

public function down()
{
echo "m190510_170403_create_shop_elasticsearch_index cannot be reverted.\n";

return false;
}
 */
}
