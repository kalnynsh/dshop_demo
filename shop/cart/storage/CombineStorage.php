<?php

namespace shop\cart\storage;

use yii\db\Connection;
use shop\cart\storage\DbStorage;
use shop\cart\storage\CookieStorage;
use shop\cart\CartItem;
use yii\web\User;

/**
 * CombineStorage class combine
 * two storages: Db and Cookie
 *
 * @property CookieStorage|DbStorage $storage
 * @property User $user
 * @property string $cookieKey
 * @property int $cookieTimeout
 * @property Connection $db
 */
class CombineStorage implements StorageInterface
{
    private $storage;
    private $user;
    private $cookieKey;
    private $cookieTimeout;
    private $db;

    public function __construct(
        User $user,
        string $cookieKey,
        int $cookieTimeout,
        Connection $db
    ) {
        $this->user = $user;
        $this->cookieKey = $cookieKey;
        $this->cookieTimeout = $cookieTimeout;
        $this->db = $db;
    }

    public function load(): array
    {
        return $this->getStorage()->load();
    }

    public function save(array $items): void
    {
        $this->getStorage()->save($items);
    }

    private function getStorage()
    {
        if ($this->storage === null) {
            $cookieStorage = $this->createCookieStorage(
                $this->cookieKey,
                $this->cookieTimeout
            );

            if ($this->user->isGuest) {
                $this->storage = $cookieStorage;
            }

            if (!$this->user->isGuest) {
                $dbStorage = $this->createDbStorage(
                    $this->user->id,
                    $this->db
                );

                if ($cookieItems = $cookieStorage->load()) {
                    $dbItems = $dbStorage->load();

                    $items = array_merge(
                        $dbItems,
                        array_udiff(
                            $cookieItems,
                            $dbItems,
                            function (CartItem $first, CartItem $second) {
                                return $first->getId() === $second->getId();
                            }
                        )
                    );

                    $dbStorage->save($items);
                    $cookieStorage->save([]);
                }

                $this->storage = $dbStorage;
            }
        }

        return $this->storage;
    }

    private function createCookieStorage($key, $timeout): CookieStorage
    {
        return new CookieStorage($key, $timeout);
    }

    private function createDbStorage(int $userId, Connection $db): DbStorage
    {
        return new DbStorage($userId, $db);
    }
}
