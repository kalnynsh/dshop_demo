<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\DataProviderInterface */
/* @var $category shop\entities\Blog\Category */

use yii\helpers\Html;

$this->title = $category->getSeoTitle();

$this->registerMetaTag(['name' =>'description', 'content' => $category->meta->description]);
$this->registerMetaTag(['name' =>'keywords', 'content' => $category->meta->keywords]);

$this->params['breadcrumbs'][] = ['label' => 'Блог', 'url' => ['index']];
$this->params['breadcrumbs'][] = $category->name;
$this->params['active_category'] = $category;
?>

<h1><?= Html::encode($category->getHeadingTitle()) ?></h1>

<?php if (trim($category->description)) : ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <?= \Yii::$app->formatter->asHtml($category->description) ?>
        </div>
    </div>
<?php endif; ?>

<?= $this->render('_list', [
    'dataProvider' => $dataProvider
]) ?>
