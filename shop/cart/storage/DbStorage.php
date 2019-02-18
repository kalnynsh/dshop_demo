<?php

namespace shop\cart\storage;

use shop\cart\CartItem;
use shop\entities\Shop\Product\Product;
use shop\repositories\Shop\ProductRepository;
use yii\db\Connection;
use yii\db\Query;

/**
 * DbStorage class - storages cart`s data in DB
 *
 * @property int               $userId
 * @property Connection        $db
 * @property ProductRepository $repository
 */
class DbStorage implements StorageInterface
{
    private $userId;
    private $db;
    private $repository;

    public function __construct(
        int $userId,
        Connection $db
    ) {
        $this->userId = $userId;
        $this->db = $db;
        $this->repository = new ProductRepository();
    }

    public function load(): array
    {
        $rows = $this->query()
            ->select('*')
            ->from('{{%shop_cart_items}}')
            ->where(['user_id' => $this->userId])
            ->orderBy(['product_id' => SORT_ASC, 'modification_id' => SORT_ASC])
            ->all($this->db);

        return array_map(function (array $row) {
            /** @var Product $product */
            if ($product = $this->repository->findOneActiveBy(
                ['id' => $row['product_id']]
            )) {
                return $this->createCartItem($product, $row['modification_id'], $row['quantity']);
            }

            return false;
        }, $rows);
    }

    public function save(array $items): void
    {
        $this->db->createCommand()->delete('{{%shop_cart_items}}', [
            'user_id' => $this->userId,
        ])->execute();

        $this->db->createCommand()->batchInsert(
            '{{%shop_cart_items}}',
            [
                'user_id',
                'product_id',
                'modification_id',
                'quantity',
            ],
            array_map(function (CartItem $item) {
                return [
                    'user_id' => $this->userId,
                    'product_id' => $item->getProductId(),
                    'modification_id' => $item->getModificationId(),
                    'quantity' => $item->getQuantity(),
                ];
            }, $items)
        )->execute();
    }

    private function createCartItem(
        Product $product,
        $modificationId,
        $quantity
    ): CartItem {
        return new CartItem($product, $modificationId, $quantity);
    }

    private function query(): Query
    {
        return new Query();
    }
}
