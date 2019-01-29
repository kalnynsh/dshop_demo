<?php

namespace shop\services\manage\Blog;

use yii\helpers\Inflector;
use shop\services\TransactionManager;
use shop\repositories\NotFoundException;
use shop\repositories\Blog\TagRepository;
use shop\repositories\Blog\PostRepository;
use shop\repositories\Blog\CategoryRepository;
use shop\forms\manage\Blog\Post\PostForm;
use shop\entities\Meta;
use shop\entities\Blog\Tag;
use shop\entities\Blog\Post\Post;

class PostManageService
{
    private $posts;
    private $categories;
    private $tags;
    private $transaction;

    public function __construct(
        PostRepository $posts,
        CategoryRepository $categories,
        TagRepository $tags,
        TransactionManager $transaction
    ) {
        $this->posts = $posts;
        $this->categories = $categories;
        $this->tags = $tags;
        $this->transaction = $transaction;
    }

    public function create(PostForm $form): Post
    {
        $category = $this->categories->get($form->categoryId);

        $post = Post::create(
            $category->id,
            $form->title,
            $form->description,
            $form->content,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );

        if ($photo = $form->photo) {
            $post->setPhoto($photo);
        }

        foreach ($form->tags->existing as $tagId) {
            $tag = $this->tags->get($tagId);
            $post->assignTag($tag->id);
        }

        $this->transaction->wrap(function () use ($post, $form) {
            foreach ($form->tags->newNames as $tagName) {
                if (!$tag = $this->tags->findByName($tagName)) {
                    $tag = $this->createTag($tagName);
                    $this->tags->save($tag);
                }

                $post->assignTag($tag->id);
            }

            $this->posts->save($post);
        });

        return $post;
    }

    public function edit($id, PostForm $form): void
    {
        $post = $this->posts->get($id);
        $category = $this->categories->get($form->categoryId);

        $post->edit(
            $category->id,
            $form->title,
            $form->description,
            $form->content,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );

        if ($photo = $form->photo) {
            $post->setPhoto($photo);
        }

        $this->transaction->wrap(function () use ($post, $form) {
            $post->revokeTags();
            $this->posts->save($post);

            foreach ($form->tags->existing as $tagId) {
                $tag = $this->tags->get($tagId);
                $post->assignTag($tag->id);
            }

            foreach ($form->tags->newNames as $tagName) {
                if (!$tag = $this->tags->findByName($tagName)) {
                    $tag = $this->createTag($tagName);
                    $this->tags->save($tag);
                }
                $post->assignTag($tag->id);
            }

            $this->posts->save($post);
        });
    }

    public function activate($id): void
    {
        $post = $this->posts->get($id);
        $post->activate();

        $this->posts->save($post);
    }

    public function draft($id): void
    {
        $post = $this->posts->get($id);
        $post->draft();

        $this->posts->save($post);
    }

    public function remove($id): void
    {
        $post = $this->posts->get($id);
        $this->posts->remove($post);
    }

    /**
     * Get Post class object by $id
     * proxy method
     *
     * @param int $id
     * @return Post
     * @throws NotFoundException
     */
    public function get($id): Post
    {
        return $this->posts->get($id);
    }

    /**
     * Find Post class object by $id
     * proxy method
     *
     * @param int $id
     * @return Post|null
     */
    public function find($id): ?Post
    {
        return $this->posts->find($id);
    }

    /**
     * Create Tag object by $tagName
     *
     * @param string $tagName
     * @return Tag
     */
    private function createTag($tagName): Tag
    {
        $slug = Inflector::slug($tagName);
        $tag = Tag::create($tagName, $slug);

        return $tag;
    }
}
