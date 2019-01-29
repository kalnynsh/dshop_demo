<?php

use kartik\widgets\FileInput;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use mihaildev\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model shop\forms\manage\Shop\Product\ProductCreateForm */

$this->title = 'Добавить Товар';
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="product-create">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype'=>'multipart/form-data']
    ]); ?>

    <div class="box box-default">
        <div class="box-header with-border">Общие данные</div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'brandId')->dropDownList($model->brandsList()) ?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <?= $form->field($model, 'description')->widget(CKEditor::class) ?>
        </div>
    </div>

    <div class="box box-default">
        <div class="box-header with-border">Склад</div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'weight')->textInput(['maxlength' => true]); ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model->quantity, 'quantity')->textInput([
                        'maxlength' => true,
                    ]); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="box box-default">
        <div class="box-header with-border">Цены</div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model->price, 'new')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model->price, 'old')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">Категории</div>
                <div class="box-body">
                    <?= $form->field($model->categories, 'main')
                        ->dropDownList($model->categories->categoriesList(), ['prompt' => '']) ?>
                    <?= $form->field($model->categories, 'others')
                        ->checkboxList($model->categories->categoriesList()) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">Теги</div>
                <div class="box-body">
                    <?= $form->field($model->tags, 'existing')->checkboxList($model->tags->tagsList()) ?>
                    <?= $form->field($model->tags, 'textNew')->textInput() ?>
                </div>
            </div>
        </div>
    </div>

    <div class="box box-default">
        <div class="box-header with-border">Характеристики</div>
        <div class="box-body">
            <?php foreach ($model->values as $idx => $value) : ?>
                <?php if ($variants = $value->variantsList()) : ?>
                    <?= $form->field($value, '[' . $idx . ']value')->dropDownList($variants, ['prompt' => '']) ?>
                <?php else : ?>
                    <?= $form->field($value, '[' . $idx . ']value')->textInput() ?>
                <?php endif ?>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="box box-default">
        <div class="box-header with-border">Фото товара</div>
        <div class="box-body">
            <?= $form->field($model->photos, 'files[]')->widget(FileInput::class, [
                'options' => [
                    'accept' => 'image/*',
                    'multiple' => true,
                ]
            ]) ?>
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
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
