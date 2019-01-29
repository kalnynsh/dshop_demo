<?php

namespace shop\entities\Content;

use yii\db\ActiveRecord;
use shop\entities\behaviors\MetaBehavior;
use shop\entities\Meta;
use shop\entities\Content\queries\PageQuery;
use paulzi\nestedsets\NestedSetsBehavior;

/**
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property Meta $meta
 *
 * @property Page $parent
 * @property Page[] $parents
 * @property Page[] $children
 * @property Page $prev
 * @property Page $next
 * @mixin NestedSetsBehavior
 */
class Page extends ActiveRecord
{
    public $meta;

    public static function create($title, $slug, $content, Meta $meta): self
    {
        $page = new static();

        $page->title = $title;
        $page->slug = $slug;
        $page->content = $content;
        $page->meta = $meta;

        return $page;
    }

    public function edit($title, $slug, $content, Meta $meta): void
    {
        $this->title = $title;
        $this->slug = $slug;
        $this->content = $content;
        $this->meta = $meta;
    }

    public function getSeoTitle(): string
    {
        return $this->meta->title ?: $this->getHeadingTitle();
    }

    public function getHeadingTitle(): string
    {
        return $this->title ?: $this->title;
    }

    public static function tableName(): string
    {
        return '{{%pages}}';
    }

    public function behaviors(): array
    {
        return [
            MetaBehavior::class,
            NestedSetsBehavior::class,
        ];
    }

    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find(): PageQuery
    {
        return new PageQuery(static::class);
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID страницы',
            'title' => 'Заголовок',
            'slug' => 'Слаг',
            'content' => 'Содержание',
        ];
    }
}
