<?php

namespace backend\controllers\shop;

use yii\web\Controller;
use yii\filters\VerbFilter;
use shop\services\manage\Shop\OrderManageService;
use shop\repositories\NotFoundException;
use shop\helpers\OrderHelper;
use shop\forms\Shop\Order\OrderEditForm;
use shop\entities\Shop\Order\Order;
use backend\forms\Shop\OrderSearch;

class OrderController extends Controller
{
    /** @property OrderManageService $service */
    private $service;
    private $yiiApp;

    public function __construct(
        $id,
        $module,
        OrderManageService $service,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->yiiApp = \Yii::$app;
    }

    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'export' => ['POST'],
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Index action return index View with data
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = $this->getOrderSearch();

        $dataProvider = $searchModel
            ->search($this->yiiApp->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * View action return view View with data
     *
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'order' => $this->getModel($id),
        ]);
    }

    /**
     * Update action return update View with data
     *
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        /** @var $order Order */
        $order = $this->getModel($id);
        $form = $this->getOrderEditForm($order);

        if ($form->load($this->yiiApp->request->post())
            && $form->validate()
        ) {
            try {
                $this->service->edit($order->id, $form);

                return $this->redirect([
                    'view',
                    'id' => $order->id,
                ]);
            } catch (\DomainException $err) {
                $this->setLogErrorFlash($err);
            }
        }

        return $this->render('update', [
            'model' => $form,
            'order' => $order,
        ]);
    }

    /**
     * Delete action - delete given $id Order object
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try {
            $this->service->remove($id);
        } catch (\DomainException $err) {
            $this->setLogErrorFlash($err);
        }

        return $this->redirect(['index']);
    }


    /**
     * Export data to exel file
     *
     * @return mixed
     */
    public function actionExport()
    {
        /** @var ActiveQuery $query */
        $query = $this->service->getQuery();

        $objPhpExcel = $this->getPhpExcel();

        $worksheet = $objPhpExcel->getActiveSheet();

        $worksheet->setCellValueByColumnAndRow(0, 1, 'ID заказа');
        $worksheet->setCellValueByColumnAndRow(1, 1, 'Создан');
        $worksheet->setCellValueByColumnAndRow(2, 1, 'Статус');

        $column = 0;
        $row = 1;

        foreach ($query->each() as $order) {
            /** @var Order $order */
            $row++;

            $worksheet->setCellValueByColumnAndRow(
                $column++,
                $row,
                $order->id
            );

            $worksheet->setCellValueByColumnAndRow(
                $column++,
                $row,
                date('Y-m-d H:i:s', $order->created_at)
            );

            $worksheet->setCellValueByColumnAndRow(
                $column++,
                $row,
                OrderHelper::statusString($order->current_status)
            );

            $column = 0;
        }

        $objWriter = \PHPExcel_IOFactory::createWriter($objPhpExcel, 'Excel2007');
        $file = tempnam(sys_get_temp_dir(), 'export');
        $objWriter->save($file);

        return $this->yiiApp->response->sendFile($file, 'orders_report.xlsx');
    }

    private function getPhpExcel(): \PHPExcel
    {
        return new \PHPExcel();
    }

    private function getOrderSearch(): OrderSearch
    {
        $service = $this->service;

        return new OrderSearch($service);
    }

    private function getOrderEditForm(Order $order): OrderEditForm
    {
        return new OrderEditForm($order);
    }

    /**
     * Get Order class object by $id
     *
     * @param int $id
     * @return Order
     * @throws NotFoundException
     */
    private function getModel($id): Order
    {
        try {
            return $this->service->get($id);
        } catch (NotFoundException $err) {
            $this->setLogErrorFlash($err);
        }
    }

    private function setLogErrorFlash($error)
    {
        $this->yiiApp->errorHandler->logException($error);
        $this->setFlash('error', $error->getMessage());
    }

    private function setFlash($type, $message)
    {
        $this->yiiApp->session->setFlash($type, $message);
    }
}
