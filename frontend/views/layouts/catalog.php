<?php

use yii\caching\TagDependency;
use frontend\widgets\Shop\CategoriesWidget;

/* @var $this \yii\web\View */
/* @var $content string */

?>
<?php $this->beginContent('@frontend/views/layouts/main.php') ?>

<div class="row">
    <aside id="column-left" class="col-sm-3 hidden-xs">
        <?php if (!$this->beginCache(
            [
                'categories-widget',
                $this->params['active_category']
                    ? $this->params['active_category']->id
                    : null
            ],
            ['dependency' => new TagDependency(
                ['tags' => ['categories']]
            )]
        )): ?>
            <?= CategoriesWidget::widget([
                'active' => $this->params['active_category'] ?? null,
            ]) ?>
        <?php $this->endCache(); ?>
        <?php endif; ?>
    </aside>
    <div id="content" class="col-sm-9">
        <?= $content ?>
    </div>
</div>

<?php $this->endContent() ?>
