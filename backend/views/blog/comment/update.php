<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $post shop\entities\Blog\Post\Post */
/* @var $comment shop\entities\Blog\Post\Comment */
/* @var $form shop\forms\manage\Blog\Post\CommentEditFormt */

$this->title = 'Редактирование комментария';
$this->params['breadcrumbs'][] = ['label' => 'Комментарии', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $post->title, 'url' => ['view', 'id' => $post->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>

<div class="comment-update">

    <?php $form = ActiveForm::begin() ?>

    <div class="box box-default">
        <div class="box-body">
            <?= $form->field($model, 'parentId')->textInput() ?>
            <?= $form->field($model, 'text')->textarea(['rows' => 20]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end() ?>

</div>
