<?php

namespace shop\services\Blog;

use shop\repositories\UserRepository;
use shop\repositories\Blog\PostRepository;
use shop\forms\Blog\CommentForm;
use shop\entities\Blog\Post\Comment;

/**
 * CommentService class
 *
 * @property PostRepository $posts
 * @property UserRepository $users
 */
class CommentService
{
    private $posts;
    private $users;

    public function __construct(PostRepository $posts, UserRepository $users)
    {
        $this->posts = $posts;
        $this->users = $users;
    }

    public function create($postId, $userId, CommentForm $form): Comment
    {
        $post = $this->posts->get($postId);
        $user = $this->users->get($userId);

        $comment = $post->addComment($user->id, $form->parentId, $form->text);
        $this->posts->save($post);

        return $comment;
    }
}
