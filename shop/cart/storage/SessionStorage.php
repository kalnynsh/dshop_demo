<?php

namespace shop\cart\storage;

use yii\web\Session;
use shop\cart\storage\StorageInterface;

class SessionStorage implements StorageInterface
{
    private $key;
    private $session;

    public function __construct($key, Session $session)
    {
        $this->key = $key;
        $this->session = $session;
    }

    public function load(): array
    {
        return $this->session->get($this->key, []);
    }

    public function save(array $items): void
    {
        $this->session->set($this->key, $items);
    }
}
