<?php

use shop\entities\Content\Page;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\Content\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Страницы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pages-index">

    <p>
        <?= Html::a('Создать страницу', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'attribute' => 'title',
                        'value' => function (Page $model) {
                            $indent = ($model->depth > 1 ? str_repeat('&nbsp;&nbsp;', $model->depth - 1) . ' ' : '');
                            return $indent . Html::a(Html::encode($model->title), ['view', 'id' => $model->id]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'value' => function (Page $model) {
                            return
                                Html::a('<span class="glyphicon glyphicon-arrow-up"></span>',
                                    [
                                        'move-up',
                                        'id' => $model->id
                                    ]
                                ) .
                                Html::a('<span class="glyphicon glyphicon-arrow-down"></span>',
                                    [
                                        'move-down',
                                        'id' => $model->id
                                    ]
                                );
                        },
                        'format' => 'raw',
                        'contentOptions' => ['style' => 'text-align: center'],
                    ],
                    'slug',
                    [
                        'attribute' => 'content',
                        'value' => function (Page $model) {
                            $content = StringHelper::truncateWords($model->content, 16);
                            return \Yii::$app->formatter->asHtml($content);
                        },
                        'format' => 'raw',
                    ],
                    ['class' => ActionColumn::class],
                ],
            ]); ?>
        </div>
    </div>
</div>
