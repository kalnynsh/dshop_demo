<?php

namespace frontend\controllers;

use yii\web\Response;
use yii\web\Controller;
use yii\helpers\Url;
use shop\services\site\Sitemap;
use shop\services\site\MapItem;
use shop\services\site\IndexItem;
use shop\readModels\Shop\ProductReadRepository;
use shop\readModels\Shop\CategoryReadRepository as ShopCategoryReadRepository;
use shop\readModels\Content\PageReadRepository;
use shop\readModels\Blog\PostReadRepository;
use shop\readModels\Blog\CategoryReadRepository as BlogCategoryReadRepository;
use shop\entities\Shop\Product\Product;
use shop\entities\Shop\Category as ShopCategory;
use shop\entities\Content\Page;
use shop\entities\Blog\Post\Post;
use shop\entities\Blog\Category as BlogCategory;

class SitemapController extends Controller
{
    const ITEMS_PER_PAGE = 100;

    private $sitemap;
    private $pages;
    private $blogCategories;
    private $posts;
    private $shopCategories;
    private $products;
    private $yiiApp;

    public function __construct(
        $id,
        $module,
        Sitemap $sitemap,
        PageReadRepository $pages,
        BlogCategoryReadRepository $blogCategories,
        PostReadRepository $posts,
        ShopCategoryReadRepository $shopCategories,
        ProductReadRepository $products,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->sitemap = $sitemap;
        $this->pages = $pages;
        $this->blogCategories = $blogCategories;
        $this->posts = $posts;
        $this->shopCategories = $shopCategories;
        $this->products = $products;
        $this->yiiApp = \Yii::$app;
    }

    public function actionIndex(): Response
    {
        return $this->renderSitemap('sitemap-index', function () {
            return $this->sitemap->generateIndex([
                new IndexItem(Url::to(['pages'], true)),
                new IndexItem(Url::to(['blog-categories'], true)),
                new IndexItem(Url::to(['blog-posts-index'], true)),
                new IndexItem(Url::to(['shop-categories'], true)),
                new IndexItem(Url::to(['shop-products-index'], true)),
            ]);
        });
    }

    public function actionPages(): Response
    {
        return $this->renderSitemap('sitemap-pages', function () {
            return $this->sitemap->generateMap(array_map(
                function (Page $page) {
                    return new MapItem(
                        Url::to(['/page/view', 'id' => $page->id], true),
                        null,
                        MapItem::WEEKLY
                    );
                },
                $this->pages->getAll()
            ));
        });
    }

    public function actionBlogCategories(): Response
    {
        return $this->renderSitemap('sitemap-blog-categories', function () {
            return $this->sitemap->generateMap(array_map(
                function (BlogCategory $category) {
                    return new MapItem(
                        Url::to(
                            [
                                '/blog/posts/category',
                                'slug' => $category->slug
                            ],
                            true
                        ),
                        null,
                        MapItem::WEEKLY
                    );
                },
                $this->blogCategories->getAll()
            ));
        });
    }

    public function actionBlogPostsIndex(): Response
    {
        return $this->renderSitemap('sitemap-blog-posts-index', function () {
            return $this->sitemap->generateIndex(
                array_map(function ($start) {
                    return new IndexItem(
                        Url::to([
                            'blog-posts',
                            'start' => $start * self::ITEMS_PER_PAGE,
                            true
                        ])
                    );
                },
                range(0, (int)($this->posts->count() / self::ITEMS_PER_PAGE))
            ));
        });
    }

    public function actionBlogPosts($start = 0): Response
    {
        return $this->renderSitemap(
            ['sitemap-blog-posts', $start],
            function () use ($start) {
                return $this->sitemap->generateMap(array_map(
                    function (Post $post) {
                        return new MapItem(
                            Url::to(
                                [
                                    '/blog/posts/post',
                                    'id' => $post->id
                                ],
                                true
                            ),
                            null,
                            MapItem::DAILY
                        );
                    },
                    $this->posts->getAllByRange($start, self::ITEMS_PER_PAGE)
                ));
            }
        );
    }

    public function actionShopCategories(): Response
    {
        return $this->renderSitemap(
            'sitemap-shop-categories',
            function () {
                return $this->sitemap->generateMap(array_map(
                    function (ShopCategory $category) {
                        return new MapItem(
                            Url::to(
                                [
                                    '/shop/catalog/category',
                                    'id' => $category->id
                                ],
                                true
                            ),
                            null,
                            MapItem::WEEKLY
                        );
                    },
                    $this->shopCategories->getAll()
                ));
            }
        );
    }

    public function actionShopProductsIndex(): Response
    {
        return $this->renderSitemap(
            'sitemap-shop-products-index',
            function () {
                return $this->sitemap->generateIndex(
                    array_map(function ($start) {
                        return new IndexItem(
                            Url::to([
                                'shop-products',
                                'start' => $start * self::ITEMS_PER_PAGE,
                                true
                            ])
                        );
                    },
                    range(0, (int)($this->products->count() / self::ITEMS_PER_PAGE))
                    )
                );
            }
        );
    }

    public function actionShopProducts($start = 0): Response
    {
        return $this->renderSitemap(
            ['sitemap-shop-products', $start],
            function () use ($start) {
                return $this->sitemap->generateMap(array_map(
                    function (Product $product) {
                        return new MapItem(
                            Url::to(
                                [
                                    '/shop/catalog/product',
                                    'id' => $product->id
                                ],
                                true
                            ),
                            null,
                            MapItem::DAILY
                        );
                    },
                    $this->products->getAllByRange($start, self::ITEMS_PER_PAGE)
                ));
            }
        );
    }

    private function renderSitemap($key, callable $callback): Response
    {
        return $this->yiiApp->response->sendContentAsFile(
            $this->yiiApp->cache->getOrSet($key, $callback),
            Url::canonical(),
            [
                'mimeType' => 'application/xml',
                'inline' => true,
            ]
        );
    }
}
