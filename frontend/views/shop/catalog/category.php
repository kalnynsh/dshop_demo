<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\DataProviderInterface */
/* @var $category shop\entities\Shop\Category */

use yii\helpers\Html;

$this->title = $category->getSeoTitle();

$this->registerMetaTag([
    'name' => 'description',
    'content' => $category->meta->description,
]);

$this->registerMetaTag([
    'name' => 'keywords',
    'content' => $category->meta->keywords,
]);

$this->params['breadcrumbs'][] = [
    'label' => 'Каталог',
    'url' => ['index']
];

$this->params['active_category'] = $category;

foreach ($category->parents as $perant) {
    if (!$perant->isRoot()) {
        $this->params['breadcrumbs'][] = [
            'label' => $perant->name,
            'url' => ['category', 'id' => $perant->id],
        ];
    }
}

$this->params['breadcrumbs'][] = $category->name;
?>

<h1><?= Html::encode($category->getHeadingTitle()) ?></h1>

<?= $this->render('_subcategories', [
    'category' => $category,
]) ?>

<?php if (trim($category->description)) : ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <?= \Yii::$app->formatter->asNtext($category->description) ?>
        </div>
    </div>
<?php endif; ?>

<?= $this->render('_list', [
    'dataProvider' => $dataProvider,
]) ?>
