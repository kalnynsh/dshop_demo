<?php

/* @var $this yii\web\View */
/* @var $post shop\entities\Blog\Post\Post */

use yii\helpers\Html;
use frontend\widgets\Blog\CommentsWidget;

$this->title = $post->getSeoTitle();

$this->registerMetaTag([
    'name' => 'description',
    'content' => $post->meta->description,
]);

$this->registerMetaTag([
    'name' => 'keywords',
    'content' => $post->meta->keywords,
]);

$this->params['breadcrumbs'][] = ['label' => 'Блог', 'url' => ['index']];
$this->params['breadcrumbs'][] = [
    'label' => $post->category->name,
    'url' => ['category', 'slug' => $post->category->slug]
];
$this->params['breadcrumbs'][] = $post->title;

$this->params['active_category'] = $post->category;

$tagLinks = [];

foreach ($post->tags as $tag) {
    $tagLinks[] = Html::a(
        Html::encode($tag->name),
        ['tag', 'slug' => $tag->slug]
    );
}
?>

<article>

    <h1><?= Html::encode($post->title) ?></h1>

    <p>
        <span class="glyphicon glyphicon-calendar"></span>
        <?= \Yii::$app->formatter->asDate($post->created_at) ?>
    </p>

    <?php if ($post->photo) : ?>
        <div>
            <img src="<?= Html::encode($post->getThumbFileUrl('photo', 'origin')) ?>"
                alt="photo" class="img-responsive"
            />
        </div>
    <?php endif; ?>

    <div>

        <?php if ($this->beginCache(['post', 'id' => $post->id], ['duration' => 0])) : ?>

            <?= \Yii::$app->formatter->asHtml($post->content, [
                'Attr.AllowedRel' => ['nofollow'],
                'HTML.SafeObject' => true,
                'Output.FlashCompat' => true,
                'HTML.SafeIframe' => true,
                'URI.SafeIframeRegexp' =>
                '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%',
            ]) ?>

            <?php $this->endCache() ?>

        <?php endif; ?>

    </div>

</article>

<p>Теги: <?= implode(', ', $tagLinks) ?></p>

<?= CommentsWidget::widget([
    'post' => $post,
]) ?>
