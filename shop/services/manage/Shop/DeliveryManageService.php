<?php

namespace shop\services\manage\Shop;

use shop\repositories\Shop\DeliveryRepository;
use shop\forms\manage\Shop\DeliveryForm;
use shop\entities\Shop\Delivery;
use shop\repositories\NotFoundException;

/**
 * DeliveryManageService class
 *
 * @property DeliveryRepository $methods
 * @property Delivery $entity
 */
class DeliveryManageService
{
    private $methods;
    private $entity;

    public function __construct(DeliveryRepository $methods)
    {
        $this->methods = $methods;
        $this->entity = new Delivery();
    }

    public function create(DeliveryForm $form): Delivery
    {
        $method = $this->entity::create(
            $form->name,
            $form->distance,
            $form->minWeight,
            $form->maxWeight,
            $form->cost,
            $form->sort
        );

        $this->methods->save($method);

        return $method;
    }

    public function edit($id, DeliveryForm $form): void
    {
        $method = $this->methods->get($id);

        $method->edit(
            $form->name,
            $form->distance,
            $form->minWeight,
            $form->maxWeight,
            $form->cost,
            $form->sort
        );

        $this->methods->save($method);
    }

    public function remove($id): void
    {
        $method = $this->methods->get($id);
        $this->methods->remove($method);
    }

    public function getDeliveryRepository(): DeliveryRepository
    {
        return $this->methods;
    }

    /**
     * Get Delivery class object by $id
     *
     * @param int $id
     * @return Delivery
     * @throws NotFoundException
     */
    public function get($id): Delivery
    {
        return $this->methods->get($id);
    }
}
