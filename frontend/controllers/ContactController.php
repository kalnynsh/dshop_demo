<?php

namespace frontend\controllers;

use yii\web\Controller;
use shop\services\ContactService;
use shop\forms\ContactForm;
use Yii;

class ContactController extends Controller
{
    private $service;
    private $yiiApp;

    public function __construct(
        $id,
        $module,
        ContactService $service,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->yiiApp = \Yii::$app;
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $form = $this->getContactForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->send($form);

                $this->setFlash(
                    'success',
                    'Thank you for contacting us. We will respond to you as soon as possible.'
                );

                return $this->goHome();
            } catch (\Exception $err) {
                $this->setLogErrorFlash($err);
            }

            return $this->refresh();
        }

        return $this->render('index', [
            'model' => $form,
        ]);
    }

    public function getContactForm(): ContactForm
    {
        return new ContactForm();
    }

    private function setFlash($type, $message)
    {
        $this->yiiApp->session->setFlash($type, $message);
    }

    private function setLogErrorFlash($error)
    {
        $this->yiiApp->errorHandler->logException($error);
        $this->setFlash('error', $error->getMessage());
    }
}
