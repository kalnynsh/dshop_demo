<?php

use yii\helpers\Html;

/** @var $this yii\web\View */
/** @var $page shop\entities\Content\Page */

$this->title = $page->getSeoTitle();

$this->registerMetaTag([
    'name' => 'description',
    'content' => $page->meta->description,
]);

$this->registerMetaTag([
    'name' => 'keywords',
    'content' => $page->meta->keywords,
]);

foreach ($page->parents as $parent) {
    if (!$parent->isRoot()) {
        $this->params['breadcrumbs'][] = [
            'label' => $parent->title,
            'url' => [
                'view',
                'id' => $parent->id,
            ],
        ];
    }
}

$this->params['breadcrumbs'][] = $page->title;
?>

<article class="page-view">

    <?php if ($this->beginCache(['page', 'id' => $page->id], ['duration' => 86400])) : ?>

        <h1><?= Html::encode($page->title) ?></h1>

        <?= \Yii::$app->formatter->asHtml($page->content, [
                'Attr.AllowedRel' => ['nofollow'],
                'HTML.SafeObject' => true,
                'Output.FlashCompat' => true,
                'HTML.SafeIframe' => true,
                'URI.SafeIframeRegexp' =>
                '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%',
            ]); ?>

        <?php $this->endCache() ?>

    <?php endif; ?>

</article>
