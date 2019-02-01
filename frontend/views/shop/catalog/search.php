<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\DataProviderInterface */
/* @var $searchForm shop\forms\Shop\Search\SearchForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Поиск';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-target="#search-panel-id">
        Расширенный поиск
        <span class="caret"></span>
    </div>
    <div class="panel-body collapse" id="search-panel-id">
        <?php $form = ActiveForm::begin(['action' => [''], 'method' => 'get']) ?>

        <div class="row">
            <div class="col-md-4">
                <?= $form->field($searchForm, 'text')->textInput() ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($searchForm, 'category')
                    ->dropDownList(
                        $searchForm->categoriesList(),
                        ['prompt' => 'Выберите категорию']
                    )
                ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($searchForm, 'brand')
                    ->dropDownList(
                        $searchForm->brandsList(),
                        ['prompt' => 'Выберите бренд']
                    )
                ?>
            </div>
        </div>

        <?php foreach ($searchForm->values as $i => $value) : ?>
            <div class="row">
                <div class="col-md-4">
                    <?= Html::encode($value->getCharacteristicName()) ?>
                </div>
                <?php if ($variants = $value->variantsList()) : ?>
                    <div class="col-md-4">
                        <?= $form->field($value, '[' . $i . ']equal')
                            ->dropDownList(
                                $variants,
                                ['prompt' => '']
                            )
                        ?>
                    </div>
                <?php elseif ($value->isAttributeSafe('from') && $value->isAttributeSafe('to')) : ?>
                    <div class="col-md-2">
                        <?= $form->field($value, '[' . $i . ']from')->textInput() ?>
                    </div>
                    <div class="col-md-2">
                        <?= $form->field($value, '[' . $i . ']to')->textInput() ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <div class="row">
            <div class="col-md-6">
                <?= Html::submitButton(
                    'Search',
                    ['class' => 'btn btn-primary btn-lg btn-block']
                ) ?>
            </div>
            <div class="col-md-6">
                <?= Html::a(
                    'Clear',
                    [''],
                    ['class' => 'btn btn-default btn-lg btn-block']
                ) ?>
            </div>
        </div>

        <?php ActiveForm::end() ?>
    </div>
</div>

<?= $this->render('_list', [
    'dataProvider' => $dataProvider,
]) ?>
