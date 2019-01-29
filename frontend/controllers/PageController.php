<?php

namespace frontend\controllers;

use yii\web\Controller;
use shop\readModels\Content\PageReadRepository;
use shop\repositories\NotFoundException;

/**
 * PageController class implements view action for Page object
 *
 * @property PageReadRepository $pages
 */
class PageController extends Controller
{
    private $pages;

    public function __construct(
        $id,
        $module,
        PageReadRepository $pages,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->pages = $pages;
    }

    public function actionView($id)
    {
        if (!$page = $this->pages->find($id)) {
            throw new NotFoundException('The requested page doesn`t exist.');
        }

        return $this->render('view', [
            'page' => $page,
        ]);
    }
}
