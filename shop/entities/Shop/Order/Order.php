<?php

namespace shop\entities\Shop\Order;

use yii\helpers\Json;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;
use shop\entities\User\User;
use shop\entities\Shop\Order\Status;
use shop\entities\Shop\Order\CustomersData;
use shop\entities\Shop\Delivery;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;

/**
 * Order class
 *
 * @property int    $id
 * @property int    $created_at
 * @property int    $user_id
 * @property int    $delivery_id
 * @property string $delivery_name
 * @property int    $delivery_cost
 * @property string $payment_method
 * @property int    $cost
 * @property string $note
 * @property int    $current_status
 * @property string $cancel_reason
 * @property CustomersData  $customersData
 * @property DeliveriesData $deliveriesData
 *
 * @property OrderItem[] $items
 * @property Status[]    $statuses
 * @property User        $user
 */
class Order extends ActiveRecord
{
    public $customersData;
    public $deliveriesData;
    public $statuses = [];

    public static function create(
        $userId,
        CustomersData $customersData,
        array $items,
        $cost,
        $note
    ): self {
        $order = new static();
        $order->user_id = $userId;
        $order->customersData = $customersData;
        $order->items = $items;
        $order->cost = $cost;
        $order->note = $note;
        $order->created_at = time();
        $order->addStatus(Status::NEW);

        return $order;
    }

    public function edit(CustomersData $customersData, $note)
    {
        $this->customersData = $customersData;
        $this->note = $note;
    }

    public function setDeliveryInfo(
        Delivery $method,
        DeliveriesData $deliveriesData
    ): void {
        $this->delivery_id = $method->id;
        $this->delivery_name = $method->name;
        $this->delivery_cost = $method->cost;
        $this->deliveriesData = $deliveriesData;
    }


    public function pending($method): void
    {
        if ($this->isPaid()) {
            throw new \DomainException('The Order has been already paid.');
        }

        $this->payment_method = $method;
        $this->addStatus(Status::PAYMENT_PENDING);
    }

    public function pay($method): void
    {
        if ($this->isPaid()) {
            throw new \DomainException('The Order has been already paid.');
        }

        $this->payment_method = $method;
        $this->addStatus(Status::PAID);
    }

    public function fail($method): void
    {
        if ($this->isPaid()) {
            throw new \DomainException('The Order has been already paid.');
        }

        if ($this->isPending()) {
            $this->payment_method = $method;
            $this->addStatus(Status::PAYMENT_FAIL);
        }
    }

    public function send(): void
    {
        if ($this->isSent()) {
            throw new \DomainException('The Order has been already sent.');
        }

        $this->addStatus(Status::SENT);
    }

    public function complete(): void
    {
        if ($this->isCompleted()) {
            throw new \DomainException('The Order has been already completed.');
        }

        $this->addStatus(Status::COMPLETED);
    }

    public function cancel($reason): void
    {
        if ($this->isCancelled()) {
            throw new \DomainException('The Order has been already cancelled.');
        }

        $this->cancel_reason = $reason;
        $this->addStatus(Status::CANCELLED);
    }

    public function getTotalCost(): int
    {
        return $this->cost + $this->delivery_cost;
    }

    public function canBePaid(): bool
    {
        return $this->isNew();
    }

    public function isNew(): bool
    {
        return $this->current_status === Status::NEW;
    }

    public function isPaid(): bool
    {
        return $this->current_status == Status::PAID;
    }

    public function isPending(): bool
    {
        return $this->current_status == Status::PAYMENT_PENDING;
    }

    public function isSent(): bool
    {
        return $this->current_status == Status::SENT;
    }

    public function isCompleted(): bool
    {
        return $this->current_status == Status::COMPLETED;
    }

    public function isCancelled(): bool
    {
        return ($this->current_status == Status::CANCELLED)
            or ($this->current_status == Status::CANCELLED_BY_CUSTOMER);
    }

    private function addStatus($value): void
    {
        $this->statuses[] = new Status($value, time());
        $this->current_status = $value;
    }

    ####################################
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getDeliveries(): ActiveQuery
    {
        return $this->hasMany(Delivery::class, ['id' => 'delivery_id']);
    }

    public function getItems(): ActiveQuery
    {
        return $this->hasMany(OrderItem::class, ['order_id' => 'id']);
    }

    ###########################################
    public static function tableName(): string
    {
        return '{{%shop_orders}}';
    }

    public function behaviors(): array
    {
        return [
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => ['items'],
            ]
        ];
    }

    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    private function createCustomersData($name, $phone): CustomersData
    {
        return new CustomersData($name, $phone);
    }

    private function createDeliveriesData($index, $address): DeliveriesData
    {
        return new DeliveriesData($index, $address);
    }

    private function createStatus($value, $createdAt): Status
    {
        return new Status($value, $createdAt);
    }

    public function afterFind():void
    {
        $this->statuses = array_map(function ($row) {
            return $this->createStatus(
                $row['value'],
                $row['created_at']
            );
        }, Json::decode($this->getAttribute('statuses_json')));

        $this->customersData = $this->createCustomersData(
            $this->getAttribute('customer_name'),
            $this->getAttribute('customer_phone')
        );

        $this->deliveriesData = $this->createDeliveriesData(
            $this->getAttribute('delivery_index'),
            $this->getAttribute('delivery_address')
        );

        parent::afterFind();
    }

    public function beforeSave($insert): bool
    {
        $this->setAttribute(
            'statuses_json',
            Json::encode(array_map(function (Status $status) {
                return [
                    'value' => $status->value,
                    'created_at' => $status->created_at,
                ];
            }, $this->statuses))
        );

        $this->setAttribute(
            'customer_name',
            $this->customersData->name
        );

        $this->setAttribute(
            'customer_phone',
            $this->customersData->phone
        );

        $this->setAttribute(
            'delivery_index',
            $this->deliveriesData->index
        );

        $this->setAttribute(
            'delivery_address',
            $this->deliveriesData->address
        );

        return parent::beforeSave($insert);
    }
}
