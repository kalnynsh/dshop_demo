<?php

namespace frontend\widgets\Blog;

use shop\entities\Blog\Post\Comment;

/**
 * CommentView DTO class for Comment entity
 *
 * @property Comment   $comment
 * @property Comment[] $children
 */
class CommentView
{
    public $comment;
    public $children;

    public function __construct(
        Comment $comment,
        array $children
    ) {
        $this->comment = $comment;
        $this->children = $children;
    }
}
