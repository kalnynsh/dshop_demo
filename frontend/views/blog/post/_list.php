<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\DataProviderInterface */

echo \yii\widgets\ListView::widget([
    'dataProvider' => $dataProvider,
    'layout' => "{items}\n{pager}",
    'itemView' => '_post',
]);
