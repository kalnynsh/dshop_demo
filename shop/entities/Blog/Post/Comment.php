<?php

namespace shop\entities\Blog\Post;

use yii\db\ActiveRecord;
use shop\entities\Blog\Post\Post;
use shop\entities\Blog\Post\queries\PostQuery;

/**
 * Comment AR class represent table 'blog_comments'
 *
 * @property int $id
 * @property int $post_id
 * @property int $user_id
 * @property int $parent_id
 * @property int $created_at
 * @property string $text
 * @property bool   $active
 *
 * @property Post $post
 */
class Comment extends ActiveRecord
{
    public static function create(
        $userId,
        $parentId,
        $text
    ): self {
        $comment = new static();
        $comment->user_id = $userId;
        $comment->parent_id = $parentId;
        $comment->text = $text;
        $comment->active = true;
        $comment->created_at = time();

        return $comment;
    }

    public function edit($parentId, $text): void
    {
        $this->parent_id = $parentId;
        $this->text = $text;
    }

    public function activate(): void
    {
        $this->active = true;
    }

    public function draft(): void
    {
        $this->active = false;
    }

    public function isActive(): bool
    {
        return $this->active == true;
    }

    public function isIdEqualTo($id): bool
    {
        return $this->id == $id;
    }

    public function isChildOf($id): bool
    {
        return $this->parent_id == $id;
    }

    public function getPost(): PostQuery
    {
        return $this->hasOne(Post::class, ['id' => 'post_id']);
    }

    public static function tableName(): string
    {
        return '{{%blog_comments}}';
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID комментария',
            'created_at' => 'Создан',
            'active' => 'Статус (активен или нет)',
            'user_id' => 'ID пользователя',
            'parent_id' => 'ID родительского поста',
            'post_id' => 'Заголовок поста',
            'text' => 'Комментарий',
        ];
    }
}
