<?php

/* @var $category shop\entities\Shop\Category */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\StringHelper;
?>

<?php if ($category->children) : ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <?php foreach ($category->children as $child) : ?>
                <a href="<?= Html::encode(Url::to(['/shop/catalog/category', 'id' => $child->id])) ?>">
                    <?= StringHelper::truncateWords(Html::encode($child->name), 2) ?>
                </a> &nbsp;
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
