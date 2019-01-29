<?php

namespace shop\forms\manage\Blog\Post;

use yii\helpers\ArrayHelper;
use yii\base\Model;
use shop\helpers\Blog\TagsHelper;
use shop\entities\Blog\Post\Post;

class TagsForm extends Model
{
    public $existing = [];
    public $textNew;

    private $helper;

    public function __construct(Post $post = null, $config = [])
    {
        if ($post) {
            $this->existing = ArrayHelper::getColumn($post->tagAssignments, 'tag_id');
        }

        $this->helper = new TagsHelper();
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            ['existing', 'each', 'rule' => ['integer']],
            ['textNew', 'string'],
            ['existing', 'default', 'value' => []],
        ];
    }

    public function tagsList(): array
    {
        return $this->helper->list();
    }

    public function getNewNames(): array
    {
        return $this->helper->splitNames($this->textNew);
    }
}
