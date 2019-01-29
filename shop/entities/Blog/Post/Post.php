<?php

namespace shop\entities\Blog\Post;

use yiidreamteam\upload\ImageUploadBehavior;
use yii\web\UploadedFile;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;
use shop\services\WaterMarker;
use shop\entities\behaviors\MetaBehavior;
use shop\entities\Meta;
use shop\entities\Blog\Tag;
use shop\entities\Blog\Post\queries\PostQuery;
use shop\entities\Blog\Post\TagAssignment;
use shop\entities\Blog\Post\Comment;
use shop\entities\Blog\Category;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;

/**
 * Post AR class represent table 'blog_posts'
 *
 * @property int $id
 * @property int $category_id
 * @property int $created_at
 * @property string $title
 * @property string $description
 * @property string $content
 * @property string $photo
 * @property int $status
 * @property int $comments_count
 *
 * @property Meta $meta
 * @property Category $category
 * @property TagAssignment[] $tagAssignments[]
 * @property Tag[] $tags
 *
 * @mixin ImageUploadBehavior
 */
class Post extends ActiveRecord
{
    const STATUS_DRAFT = 0;
    const STATUS_ACTIVE = 1;

    public $meta;

    public static function create(
        $categoryId,
        $title,
        $description,
        $content,
        Meta $meta
    ): self {
        $post = new static();
        $post->category_id = $categoryId;
        $post->title = $title;
        $post->description = $description;
        $post->content = $content;
        $post->meta = $meta;
        $post->status = self::STATUS_DRAFT;
        $post->created_at = time();
        $post->comments_count = 0;

        return $post;
    }

    public function setPhoto(UploadedFile $photo): void
    {
        $this->photo = $photo;
    }

    public function edit(
        $categoryId,
        $title,
        $description,
        $content,
        Meta $meta
    ): void {
        $this->category_id = $categoryId;
        $this->title = $title;
        $this->description = $description;
        $this->content = $content;
        $this->meta = $meta;
    }

    public function activate(): void
    {
        if ($this->isActive()) {
            throw new \DomainException('Post is already active.');
        }
        $this->status = self::STATUS_ACTIVE;
    }

    public function draft(): void
    {
        if ($this->isDraft()) {
            throw new \DomainException('Post is already draft.');
        }
        $this->status = self::STATUS_DRAFT;
    }

    public function isActive(): bool
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    public function isDraft(): bool
    {
        return $this->status == self::STATUS_DRAFT;
    }

    public function getSeoTitle(): string
    {
        return $this->meta->title ?: $this->title;
    }

    // Tags
    public function assignTag($id): void
    {
        $assignments = $this->tagAssignments;

        foreach ($assignments as $assignment) {
            if ($assignment->isForTag($id)) {
                return;
            }
        }

        $assignments[] = TagAssignment::create($id);
        $this->tagAssignments = $assignments;
    }

    public function revokeTag($id): void
    {
        $assignments = $this->tagAssignments;

        foreach ($assignments as $idx => $assignment) {
            if ($assignment->isForTag($id)) {
                unset($assignments[$idx]);
                $this->tagAssignments = $assignments;

                return;
            }
        }

        throw new \DomainException('Assignment is not found.');
    }

    public function revokeTags(): void
    {
        $this->tagAssignments = [];
    }

    ## Comments manipulation ##
    public function addComment($userId, $parentId, $text): Comment
    {
        $parentComment = $parentId ? $this->getComment($parentId) : null;

        if ($parentComment && !$parentComment->isActive()) {
            throw new \DomainException('Can`t add comment to inactive parent comment.');
        }

        $comments = $this->comments;

        $parentID = $parentComment ? $parentComment->id : null;
        $newComment = Comment::create($userId, $parentID, $text);
        $comments[] = $newComment;

        $this->updateComments($comments);

        return $newComment;
    }

    public function editComment($id, $parentId, $text): void
    {
        $parentComment = $parentId ? $this->getComment($parentId) : null;
        $comments = $this->comments;

        foreach ($comments as $comment) {
            if ($comment->isIdEqualTo($id)) {
                $comment->edit($parentComment ? $parentComment->id : null, $text);

                $this->updateComments($comments);
                return;
            }
        }

        throw new \DomainException('Comment not found');
    }

    public function activateComment($id): void
    {
        $comments = $this->comments;

        foreach ($comments as $comment) {
            if ($comment->isIdEqualTo($id)) {
                $comment->activate();
                $this->updateComments($comments);

                return;
            }
        }

        throw new \DomainException('Comment not found');
    }

    public function removeComment($id): void
    {
        $comments = $this->comments;

        foreach ($comments as $idx => $comment) {
            if ($comment->isIdEqualTo($id)) {
                if ($this->hasChildren($comment->id)) {
                    $comment->draft();
                } else {
                    unset($comments[$idx]);
                }

                $this->updateComments($comments);
                return;
            }
        }

        throw new \DomainException('Comment not found');
    }

    public function getComment($id): Comment
    {
        foreach ($this->comments as $comment) {
            if ($comment->isIdEqualTo($id)) {
                return $comment;
            }
        }

        throw new \DomainException('Comment not found');
    }

    private function hasChildren($id): bool
    {
        foreach ($this->comments as $comment) {
            if ($comment->isChildOf($id)) {
                return true;
            }
        }

        return false;
    }

    private function updateComments(array $comments): void
    {
        $this->comments = $comments;
        $this->comments_count = count(array_filter($comments, function (Comment $comment) {
            return $comment->isActive();
        }));
    }

    ## Links ##
    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    public function getTagAssignments(): ActiveQuery
    {
        return $this->hasMany(TagAssignment::class, ['post_id' => 'id']);
    }

    public function getTags(): ActiveQuery
    {
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])->via('tagAssignments');
    }

    public function getComments(): ActiveQuery
    {
        return $this->hasMany(Comment::class, ['post_id' => 'id']);
    }

    ## Table, behaviors ##
    public static function tableName(): string
    {
        return '{{%blog_posts}}';
    }

    public function behaviors(): array
    {
        return [
            MetaBehavior::class,
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => ['tagAssignments', 'comments',],
            ],
            [
                'class' => ImageUploadBehavior::class,
                'attribute' => 'photo',
                'createThumbsOnRequest' => true,
                'filePath' => '@staticRoot/origin/posts/[[id]].[[extension]]',
                'fileUrl' => '@static/origin/posts/[[id]].[[extension]]',
                'thumbPath' => '@staticRoot/cache/posts/[[profile]]_[[id]].[[extension]]',
                'thumbUrl' => '@static/cache/posts/[[profile]]_[[id]].[[extension]]',
                'thumbs' => [
                    'admin' => ['width' => 100, 'height' => 70],
                    'thumb' => ['width' => 640, 'height' => 480],
                    'blog_list' => ['width' => 1000, 'height' => 150],
                    'widget_list' => ['width' => 228, 'height' => 228],
                    'origin' => [
                        'processor' => [
                            new WaterMarker(1024, 768, '@frontend/web/image/logoWM.png'),
                            'process'
                        ],
                    ],
                ],
            ],
        ];
    }

    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find(): PostQuery
    {
        return new PostQuery(static::class);
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID поста',
            'category_id' => 'ID категории',
            'created_at' => 'Создан',
            'title' => 'Заголовок',
            'description' => 'Описание',
            'content' => 'Содержание',
            'status' => 'Статус',
            'comments_count' => 'Количество комментариев',
        ];
    }
}
