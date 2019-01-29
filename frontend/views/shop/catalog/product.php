<?php

use yii\helpers\Url;
use yii\helpers\StringHelper;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use shop\helpers\PriceHelper;
use frontend\assets\MagnificPopupInitAsset;

/* @var $this yii\web\View */
/* @var $product shop\entities\Shop\Product\Product */

$this->title = StringHelper::truncateWords($product->name, 4);

$this->registerMetaTag([
    'name' => 'description',
    'content' => $product->meta->description,
]);

$this->registerMetaTag([
    'name' => 'keywords',
    'content' => $product->meta->keywords,
]);

$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['index']];

foreach ($product->category->parents as $perant) {
    if (!$perant->isRoot()) {
        $this->params['breadcrumbs'][] = [
            'label' => $perant->name,
            'url' => ['category', 'id' => $perant->id],
        ];
    }
}

$this->params['breadcrumbs'][] = [
    'label' => $product->category->name,
    'url' => ['category', 'id' => $product->category->id],
];

$this->params['breadcrumbs'][] = $this->title;
$this->params['active_category'] = $product->category;

MagnificPopupInitAsset::register($this);
?>

 <div class="row" xmlns:fb="http://www.w3./1999/xhtml">
    <div class="col-sm-8">
        <ul class="thumbnails">
            <?php foreach ($product->photos as $idx => $photo) : ?>
                <?php if ($idx == 0) : ?>
                    <li>
                        <a class="thumbnail" href="<?= $photo->getThumbFileUrl('file', 'catalog_origin') ?>" >
                            <img src="<?= $photo->getThumbFileUrl('file', 'catalog_product_main') ?>"
                                alt="<?= Html::encode($product->name) ?>" />
                        </a>
                    </li>
                <?php else : ?>
                    <li class="image-additional">
                        <a class="thumbnail" href="<?= $photo->getThumbFileUrl('file', 'catalog_origin') ?>" >
                            <img src="<?= $photo->getThumbFileUrl('file', 'catalog_product_additional') ?>"
                                alt="product additional image" />
                        </a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-description" data-toggle="tab">Описание</a></li>
            <li><a href="#tab-specification" data-toggle="tab">Характеристики</a></li>
            <li><a href="#tab-review" data-toggle="tab">Отзывы</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab-description">

            <?php if ($this->beginCache(['product', 'id' => $product->id], ['duration' => 0])) : ?>
                <p>
                    <?= \Yii::$app->formatter->asHtml($product->description, [
                        'Attr.AllowedRel' => ['nofollow'],
                        'HTML.SafeObject' => true,
                        'Output.FlashCompat' => true,
                        'HTML.SafeIframe' => true,
                        'URI.SafeIframeRegexp' =>
                        '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%',
                    ]) ?>
                </p>
                <?php $this->endCache() ?>
            <?php endif; ?>

            </div>
            <div class="tab-pane" id="tab-specification">
                <table class="table table-bordered">
                    <tbody>
                        <?php foreach ($product->values as $value) : ?>
                            <?php if (!empty($value->value)) : ?>
                                <tr>
                                    <th><?= Html::encode($value->characteristic->name) ?></th>
                                    <td><?= Html::encode(str_replace('_', ' ', $value->value)) ?></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane" id="tab-review">
                <div id="review">
                    <h2>Write a review</h2>
                    <?php if (Yii::$app->user->isGuest) : ?>
                        <div class="panel-panel-info">
                            <div class="panel-body">
                                Для отзыва пожалуйста <?= Html::a('авторизуйтесь', ['/auth/auth/login']) ?>.
                            </div>
                        </div>
                    <?php else : ?>
                        <?php $form = ActiveForm::begin(['id' => 'form-review']) ?>

                            <?= $form->field($reviewForm, 'vote')->dropDownList(
                                $reviewForm->votesList(),
                                ['prompt' => '--- Выберите оценку ---']
                            ) ?>

                            <?= $form->field($reviewForm, 'text')->textarea(['row' => 5]) ?>

                            <div class="form-group">
                                <?= Html::submitButton('Send', ['class' => 'btn btn-primary btn-lg btn-block']) ?>
                            </div>
                        <?php ActiveForm::end() ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <p class="btn-group">
            <button type="button" data-toggle="tooltip" title="Добавить в список желаний"
                class="btn btn-default"
                href="<?= Url::to(['/cabinet/wishlist/add', 'id' => $product->id]) ?>"
                data-method="post">
                    <i class="fa fa-heart"></i>
            </button>
            <!-- <button type="button" data-toggle="tooltip" title="Compare this Product"
                class="btn btn-default"
                onclick="compare.add('47');">
                    <i class="fa fa-exchange"></i>
            </button> -->
        </p>
        <h1><?= Html::encode($product->name) ?></h1>
        <ul class="list-unstyled">
            <li>
                Бренд:
                <a href="<?= Html::encode(Url::to(['brand' , 'id' => $product->brand->id])) ?>">
                    <?= Html::encode($product->brand->name) ?>
                </a>
            </li>
            <li>
                Теги:
                <?php foreach ($product->tags as $tag) : ?>
                    &nbsp;
                    <a href="<?= Html::encode(Url::to(['tag', 'id' => $tag->id])) ?>">
                        <?= Html::encode($tag->name) ?>
                    </a>
                <?php endforeach; ?>
            </li>
            <li>Код товара: <?= Html::encode($product->code) ?></li>
        </ul>
        <ul class="list-unstyled">
            <li class="product-price">
                <?= PriceHelper::format($product->price_new) ?>
                &nbsp;<i class="fa fa-rub" aria-hidden="true"></i>
            </li>
        </ul>
        <div id="product">
            <?php if ($product->isAvailable()) : ?>
                <hr>
                <h3>Доступные опции</h3>
                <?php $form = ActiveForm::begin([
                    'action' => ['/shop/cart/add', 'id' => $product->id],
                ]) ?>

                <?php if ($modifications = $cartForm->modificationsList()) : ?>
                    <?= $form->field($cartForm, 'modification')
                        ->dropDownList(
                            $modifications,
                            ['prompt' => '--- Выбор ---']
                        ) ?>
                <?php endif ?>

                <?= $form->field($cartForm, 'quantity')->textInput() ?>

                <div class="form-group">
                    <?= Html::submitButton('Добавить в корзину', ['class' => 'btn btn-primary btn-lg btn-block']) ?>
                </div>
                <?php ActiveForm::end() ?>

            <?php else : ?>

                <div class="alert alert-danger">
                    <p>На данный момент этот товар не доступен.</p>
                    <p>Можете его добавить в список желаний.</p>
                </div>

            <?php endif; ?>
        </div>
        <div class="rating">
            <p>
                <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                <a href="" onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;">
                    0 отзывов
                </a>
                / <a href="" onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;">
                    Напишите отзыв
                </a>
            </p>
            <hr>
            <!-- AddThis Button BEGIN -->
            <!-- <div class="addthis_toolbox addthis_default_style"
                data-url="/index.php?route=product/product&amp;product_id=47">
                <a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
                <a class="addthis_button_tweet"></a>
                <a class="addthis_button_pinterest_pinit"></a>
                <a class="addthis_counter addthis_pill_style"></a>
            </div>
            <script type="text/javascript" src="#"></script> -->
            <!-- AddThis Button END -->
        </div>
    </div>
</div>
