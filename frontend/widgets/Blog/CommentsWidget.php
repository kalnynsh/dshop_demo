<?php

namespace frontend\widgets\Blog;

use yii\base\Widget;
use yii\base\InvalidConfigException;
use shop\forms\Blog\CommentForm;
use shop\entities\Blog\Post\Post;
use frontend\widgets\Blog\CommentView;

/**
 * CommentsWidget class - render comments
 *
 * @property Post $post
 */
class CommentsWidget extends Widget
{
    public $post;

    public function init(): void
    {
        if (!$this->post) {
            throw new InvalidConfigException('Do not given the post.');
        }
    }

    public function run(): string
    {
        $form = $this->getCommentForm();

        $comments = $this
            ->post
            ->getComments()
            ->orderBy([
                'parent_id' => SORT_ASC,
                'id' => SORT_ASC,
            ])
            ->all();

        if (!empty($comments)) {
            $items = $this->getTreeRecursive($comments, null);
        }

        if (empty($comments)) {
            $items = [];
        }

        return $this->render('comments/comments', [
            'post' => $this->post,
            'items' => $items,
            'commentForm' => $form,
        ]);
    }

    /**
     * @param Comment[] $comments
     * @param integer   $parentId
     * @return CommentView[]
     */
    public function getTreeRecursive(&$comments, $parentId): array
    {
        $items = [];

        foreach ($comments as $comment) {
            if ($comment->parent_id == $parentId) {
                $items[] = $this->getCommentView(
                    $comment,
                    $this->getTreeRecursive($comments, $comment->id)
                );
            }
        }

        return $items;
    }

    private function getCommentForm(): CommentForm
    {
        return new CommentForm();
    }

    private function getCommentView($comment, $children): CommentView
    {
        return new CommentView($comment, $children);
    }
}
