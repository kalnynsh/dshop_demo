<?php

namespace shop\repositories\Blog;

use shop\entities\Blog\Post\Post;
use shop\repositories\NotFoundException;

class PostRepository
{
    public function get($id): Post
    {
        if (!$post = Post::findOne($id)) {
            throw new NotFoundException('Post was not found.');
        }

        return $post;
    }

    public function find($id): ?Post
    {
        return Post::findOne($id);
    }

    public function existsByCategory($id): bool
    {
        return Post::find()->andWhere(['category_id' => $id])->exists();
    }

    public function save(Post $post): void
    {
        if (!$post->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    public function remove(Post $post): void
    {
        if (!$post->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }
}
