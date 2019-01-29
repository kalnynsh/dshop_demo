<?php

namespace shop\forms\manage\Blog\Post;

use yii\base\Model;
use shop\entities\Blog\Post\Post;
use shop\entities\Blog\Post\Comment;

/**
 * CommentEditForm class DTO for $comment
 *
 * @property int $parentId
 * @property string $text
 */
class CommentEditForm extends Model
{
    public $parentId;
    public $text;

    public function __construct(Comment $comment, $config = [])
    {
        parent::__construct($config);
        $this->parentId = $comment->parent_id;
        $this->text = $comment->text;
    }

    public function rules(): array
    {
        return [
            ['text', 'required'],
            ['text', 'string'],
            ['parentId', 'integer'],
        ];
    }
}
