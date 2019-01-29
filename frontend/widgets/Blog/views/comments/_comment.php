<?php

/* @var $this yii\web\View */
/* @var $item shop\forms\Blog\CommentView */
?>

<div class="comment-item" data-id="<?= $item->comment->id ?>" >

    <div class="panel panel-default">
        <div class="panel-body">
            <p class="comment-content">
                <?php if ($item->comment->isActive()) : ?>
                    <?= \Yii::$app->formatter->asNtext($item->comment->text) ?>
                <?php else : ?>
                   <i>Комментарий был удален</i>
                <?php endif ?>
            </p>
            <div class="pull-left">
                <?= \Yii::$app->formatter->asDatetime($item->comment->created_at) ?>
            </div>
            <div class="pull-right">
                <span class="comment-reply">Ответить</span>
            </div>
        </div>
    </div>

    <div class="margin">
        <div class="reply-block"></div>
        <div class="comments">
            <?php foreach ($item->children as $child) : ?>
                <?= $this->render('_comment', ['item' => $child]) ?>
            <?php endforeach; ?>
        </div>
    </div>

</div>
