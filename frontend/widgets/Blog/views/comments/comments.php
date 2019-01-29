<?php

/* @var $this yii\web\View */
/* @var $post shop\entities\Blog\Post\Post */
/* @var $items shop\forms\Blog\CommentView[] */
/* @var $commentForm shop\forms\Blog\CommentForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>

<div id="comments" class="inner-bottom-xs">
    <h2>Комментарии</h2>
    <?php if ($items) : ?>
        <?php foreach ($items as $item) : ?>
            <?= $this->render('_comment', ['item' => $item]) ?>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Пока нет комметариев</p>
    <?php endif; ?>
</div>

<div id="reply-block" class="leave-reply">

    <?php $form = ActiveForm::begin([
        'action' => ['comment', 'id' => $post->id],
    ]) ?>

        <?= Html::activeHiddenInput($commentForm, 'parentId') ?>
        <?= $form->field($commentForm, 'text')->textarea(['rows' => 5]) ?>

        <div class="form-group">
            <?= Html::submitButton('Добавить комментарий', [
                'class' => 'btn btn-primary',
            ]) ?>
        </div>

    <?php ActiveForm::end() ?>

</div>

<?php $this->registerJs("
    jQuery(document).on('click', '#comments .comment-reply', function () {
        var link = jQuery(this);
        var form = jQuery('#reply-block');
        var comment = link.closest('.comment-item');

        jQuery('#commentform-parentid').val(comment.data('id'));
        form.detach().appendTo(comment.find('.reply-block:first'));

        return false;
    });
"); ?>
