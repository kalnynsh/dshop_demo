<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use shop\helpers\UserHelper;
use shop\entities\User\User;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать пользователя', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'id',
                    [
                        'attribute' => 'created_at',
                        'filter' => DatePicker::widget([
                            'model' => $searchModel,
                            'attribute' => 'date_from',
                            'attribute2' => 'date_to',
                            'type' => DatePicker::TYPE_RANGE,
                            'separator' => '-',
                            'pluginOptions' => [
                                'todayHighlight' => true,
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd',
                            ],
                        ]),
                        'format' => 'datetime',
                    ],
                    [
                        'attribute' => 'username',
                        'value' => function (User $model) {
                            return Html::a(
                                Html::encode($model->username),
                                ['view', 'id' => $model->id]
                            );
                        },
                        'format' => 'raw',
                    ],
                    'email:email',
                    [
                        'attribute' => 'role',
                        'filter' => UserHelper::rolesList(),
                        'value' => function (User $model) {
                            return UserHelper::roleLabelByUser($model->id);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'status',
                        'filter' => UserHelper::statusList(),
                        'value' => function (User $model) {
                            return UserHelper::statusLabel($model->status);
                        },
                        'format' => 'raw',
                    ],

                    ['class' => ActionColumn::class],
                ],
            ]); ?>
        </div>
    </div>
</div>
