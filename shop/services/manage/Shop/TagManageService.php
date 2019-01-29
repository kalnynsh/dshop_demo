<?php

namespace shop\services\manage\Shop;

use shop\repositories\Shop\TagRepository;
use shop\repositories\NotFoundException;
use shop\forms\manage\Shop\TagForm;
use shop\entities\Shop\Tag;

class TagManageService
{
    /** @property TagRepository $tags */
    private $tags;

    public function __construct(TagRepository $tags)
    {
        $this->tags = $tags;
    }

    public function create(TagForm $form): Tag
    {
        $tag = Tag::create(
            $form->name,
            $form->slug
        );
        $this->tags->save($tag);

        return $tag;
    }

    public function edit($id, TagForm $form): void
    {
        $tag = $this->tags->get($id);

        $tag->edit(
            $form->name,
            $form->slug
        );

        $this->tags->save($tag);
    }

    public function remove($id): void
    {
        $tag = $this->tags->get($id);
        $this->tags->remove($tag);
    }

    public function getTagRepository()
    {
        return $this->tags;
    }

    /**
     * Get Tag class object by $id
     *
     * @param integer $id
     * @return Tag - model`s entity
     * @throws NotFoundException if the entity cannot be found
     */
    public function get($id): Tag
    {
        return $this->tags->get($id);
    }
}
