<?php

/* @var $this yii\web\View */
/* @var $model shop\entities\Blog\Post\Post */

use yii\helpers\Html;
use yii\helpers\Url;

$url = Url::to(['post', 'id' => $model->id]);
?>

<div class="blog-posts-item">
    <?php if ($model->photo) : ?>
        <div>
            <a href="<?= Html::encode($url) ?>">
                <img src="<?= Html::encode($model->getThumbFileUrl('photo', 'blog_list')) ?>"
                    alt="photo" class="img-responsive"
                />
            </a>
        </div>
    <?php endif; ?>

    <h2 class="h2">
        <a href="<?= Html::encode($url) ?>">
            <?= Html::encode($model->title) ?>
        </a>
    </h2>

    <div>
        <?= \Yii::$app->formatter->asNtext($model->description) ?>
    </div>
</div>
