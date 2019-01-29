<?php

namespace shop\forms\manage\Blog\Post;

use yii\web\UploadedFile;
use shop\helpers\Blog\CategoriesHelper;
use shop\forms\manage\MetaForm;
use shop\forms\CompositeForm;
use shop\entities\Blog\Post\Post;

/**
 * PostForm class TDO for Post entity
 *
 * @property int $categoryId
 * @property string $title
 * @property string $description
 * @property string $content
 * @property string $photo
 *
 * @property MetaForm $meta
 * @property TagsForm $tags
 */
class PostForm extends CompositeForm
{
    public $categoryId;
    public $title;
    public $description;
    public $content;
    public $photo;

    public function __construct(Post $post = null, $config = [])
    {
        if ($post) {
            $this->categoryId = $post->category_id;
            $this->title = $post->title;
            $this->description = $post->description;
            $this->content = $post->content;
            $this->meta = new MetaForm($post->meta);
            $this->tags = new TagsForm($post);
        }

        if (!$post) {
            $this->meta = new MetaForm();
            $this->tags = new TagsForm();
        }

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['categoryId', 'title'], 'required'],
            [['title'], 'string', 'max' => 255],
            ['categoryId', 'integer'],
            [['description', 'content'], 'string'],
            ['photo', 'image'],
        ];
    }

    public function internalForms(): array
    {
        return ['meta', 'tags'];
    }

    public function categoriesList(): array
    {
        return CategoriesHelper::list();
    }

    public function beforeValidate(): bool
    {
        if (parent::beforeValidate()) {
            $this->photo = UploadedFile::getInstance($this, 'photo');

            return true;
        }

        return false;
    }
}
