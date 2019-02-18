<?php

namespace api\providers;

use yii\base\BaseObject;
use yii\data\DataProviderInterface;

/**
 * MapDataProvider class decorator
 * for DataProviderInterface object mapping
 *
 * @property int              $count
 * @property array            $keys
 * @property array            $models
 * @property Pagination|false $pagination
 * @property Sort|bool        $sort
 * @property int              $totalCount
 * @property DataProviderInterface $origin
 * @property callable         $callback
 */
class MapDataProvider extends BaseObject implements DataProviderInterface
{
    private $origin;
    private $callback;

    public function __construct(DataProviderInterface $origin, callable $callback)
    {
        parent::__construct();
        $this->origin = $origin;
        $this->callback = $callback;
    }

    public function prepare($forcePrepare = false): void
    {
        $this->origin->prepare($forcePrepare);
    }

    public function getCount(): int
    {
        return $this->origin->getCount();
    }

    public function getTotalCount(): int
    {
        return $this->origin->getTotalCount();
    }

    public function getModels(): array
    {
        return array_map($this->callback, $this->origin->getModels());
    }

    public function getKeys(): array
    {
        return $this->origin->getKeys();
    }

    public function getSort()
    {
        return $this->origin->getSort();
    }

    public function getPagination()
    {
        return $this->origin->getPagination();
    }
}
