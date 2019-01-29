<?php

namespace shop\services\manage\Blog;

use yii\db\ActiveQuery;
use shop\repositories\Blog\PostRepository;
use shop\forms\manage\Blog\Post\CommentEditForm;
use shop\entities\Blog\Post\Post;
use shop\entities\Blog\Post\Comment;

/**
 * CommentManageService manage Comment entity
 *
 * @property PostRepository $posts
 */
class CommentManageService
{
    private $posts;

    public function __construct(PostRepository $posts)
    {
        $this->posts = $posts;
    }

    public function edit($postId, $id, CommentEditForm $form): void
    {
        $post = $this->posts->get($postId);

        $post->editComment(
            $id,
            $form->parentId,
            $form->text
        );

        $this->posts->save($post);
    }

    public function activate($postId, $id): void
    {
        $post = $this->posts->get($postId);
        $post->activateComment($id);
        $this->posts->save($post);
    }

    public function remove($postId, $id): void
    {
        $post = $this->posts->get($postId);
        $post->removeComment($id);
        $this->posts->save($post);
    }

    /**
     * Find Post object by id
     *
     * @param int $postId
     * @return Post|null
     */
    public function findPost($postId): ?Post
    {
        return $this->posts->find($postId);
    }

    public function getCommentQuery(): ActiveQuery
    {
        return Comment::find()->with(['post']);
    }
}
