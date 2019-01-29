<?php

use kartik\file\FileInput;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use mihaildev\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model shop\forms\manage\Blog\Post\PostForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

        <div class="row">
            <div class="col-md-6">
                <div class="box box-default">
                    <div class="box-header with-border">Категории</div>
                    <div class="box-body">
                        <?= $form->field($model, 'categoryId')
                            ->dropDownList(
                                $model->categoriesList(),
                                ['prompt' => '-- Выбор категории --']
                            ) ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="box box-default">
                    <div class="box-header with-border">Теги</div>
                    <div class="box-body">
                        <?= $form->field($model->tags, 'existing')
                            ->checkboxList($model->tags->tagsList()) ?>
                        <?= $form->field($model->tags, 'textNew')->textInput() ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-default">
            <div class="box-header with-border">Пост</div>
            <div class="box-body">
                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'description')->textarea(['rows' => 5]) ?>
                <?= $form->field($model, 'content')->widget(CKEditor::class, [
                    'editorOptions' => [
                        'preset' => 'full',
                        'inline' => false,
                    ],
                ]) ?>
            </div>
        </div>

        <div class="box box-default">
            <div class="box-header with-border">Фото</div>
            <div class="box-body">
                <?= $form->field($model, 'photo')
                    ->label(false)
                    ->widget(FileInput::class, [
                        'options' => [
                            'accept' => 'image/*',
                        ]
                    ])
                ?>
            </div>
        </div>

        <div class="box box-default">
            <div class="box-header with-border">СЕО (SEO)</div>
            <div class="box-body">
                <?= $form->field($model->meta, 'title')->textInput() ?>
                <?= $form->field($model->meta, 'description')->textarea(['rows' => 2]) ?>
                <?= $form->field($model->meta, 'keywords')->textInput() ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
