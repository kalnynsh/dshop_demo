<?php

namespace backend\bootstrap;

use yii\base\BootstrapInterface;
use mihaildev\elfinder\ElFinder;
use mihaildev\ckeditor\CKEditor;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $container = \Yii::$container;

        $container->set(CKEditor::class, [
            'editorOptions' => ElFinder::ckeditorOptions('elfinder'),
        ]);
    }
}
