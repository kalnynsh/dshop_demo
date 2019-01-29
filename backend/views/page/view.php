<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $page shop\entities\Content\Page */

$this->title = $page->title;
$this->params['breadcrumbs'][] = ['label' => 'Страницы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-view">

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $page->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $page->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы точно хотите удалить эти данные?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="box">
        <div class="box-header with-border">Общие данные</div>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $page,
                'attributes' => [
                    'id',
                    'title',
                    'slug',
                ],
            ]) ?>
        </div>
    </div>

    <div class="box">
        <div class="box-header with-border">Содержание</div>
        <div class="box-body">
            <?= \Yii::$app->formatter->asHtml($page->content, [
                'Attr.AllowedRel' => ['nofollow'],
                'HTML.SafeObject' => true,
                'Output.FlashCompat' => true,
                'HTML.SafeIframe' => true,
                'URI.SafeIframeRegexp'
                => '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%',
            ]) ?>
        </div>
    </div>

    <div class="box">
        <div class="box-header with-border">СЕО (SEO)</div>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $page,
                'attributes' => [
                    'meta.title',
                    'meta.description',
                    'meta.keywords',
                ],
            ]) ?>
        </div>
    </div>
</div>
