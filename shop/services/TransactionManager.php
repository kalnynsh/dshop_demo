<?php

namespace shop\services;

class TransactionManager
{
    private $dispatcher;
    private $yiiApp;

    public function __construct(DeferredEventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
        $this->yiiApp = \Yii::$app;
    }

    public function wrap(callable $function): void
    {
        $transaction = $this->yiiApp-db->beginTransaction();

        try {
            $this->dispatcher->defer();
            $function();

            $transaction->commit();
            $this->dispatcher->release();
        } catch (\Exception $error) {
            $transaction->rollBack();
            $this->dispatcher->clean();

            throw $error;
        }
    }
}
