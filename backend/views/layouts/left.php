<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>Admin Adminovich</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => [
                    'class' => 'sidebar-menu tree',
                    'data-widget'=> 'tree'
                ],
                'items' => [
                    ['label' => 'Управление', 'options' => ['class' => 'header']],
                    [
                        'label' => 'Магазин',
                        'icon' => 'folder',
                        'url' => '#',
                        'items' => [
                            [
                                'label' => 'Заказы',
                                'icon' => 'cart-plus',
                                'url' => ['/shop/order/index'],
                                'active' => ($this->context->id == 'shop/order'),
                            ],
                            [
                                'label' => 'Товары',
                                'icon' => 'database',
                                'url' => ['/shop/product/index'],
                                'active' => ($this->context->id == 'shop/product'),
                            ],
                            [
                                'label' => 'Бренды',
                                'icon' => 'file-o',
                                'url' => ['/shop/brand/index'],
                                'active' => ($this->context->id == 'shop/brand'),
                            ],
                            [
                                'label' => 'Теги товаров',
                                'icon' => 'tags',
                                'url' => ['/shop/tag/index'],
                                'active' => ($this->context->id == 'shop/tag'),
                            ],
                            [
                                'label' => 'Категории товаров',
                                'icon' => 'cubes',
                                'url' => ['/shop/category/index'],
                                'active' => ($this->context->id == 'shop/category'),
                            ],
                            [
                                'label' => 'Характеристики товаров',
                                'icon' => 'server',
                                'url' => ['/shop/characteristic/index'],
                                'active' => ($this->context->id == 'shop/characteristic'),
                            ],
                            [
                                'label' => 'Доставка',
                                'icon' => 'anchor',
                                'url' => ['/shop/delivery/index'],
                                'active' => ($this->context->id == 'shop/delivery'),
                            ],
                        ],
                    ],
                    [
                        'label' => 'Блог',
                        'icon' => 'book',
                        'items' => [
                            [
                                'label' => 'Теги блога',
                                'icon' => 'bookmark',
                                'url' => ['/blog/tag/index'],
                                'active' => ($this->context->id == 'blog/tag'),
                            ],
                            [
                                'label' => 'Категории блога',
                                'icon' => 'flag-checkered',
                                'url' => ['/blog/category/index'],
                                'active' => ($this->context->id == 'blog/category'),
                            ],
                            [
                                'label' => 'Посты блога',
                                'icon' => 'book',
                                'url' => ['/blog/post/index'],
                                'active' => ($this->context->id == 'blog/post'),
                            ],
                            [
                                'label' => 'Комментарии блога',
                                'icon' => 'commenting-o',
                                'url' => ['/blog/comment/index'],
                                'active' => ($this->context->id == 'blog/comment'),
                            ],
                        ],
                    ],
                    [
                        'label' => 'Контент',
                        'icon' => 'info-circle',
                        'items' => [
                            [
                                'label' => 'Статические страницы',
                                'icon' => 'pencil-square',
                                'url' => ['/page/index'],
                                'active' => ($this->context->id == 'page'),
                            ],
                            [
                                'label' => 'Файлы',
                                'icon' => 'file-code-o',
                                'url' => ['/file/index'],
                                'active' => ($this->context->id == 'file'),
                            ],
                        ],
                    ],
                    [
                        'label' => 'Пользователи',
                        'icon' => 'users',
                        'url' => ['/user/index'],
                        'active' => ($this->context->id == 'user'),
                    ],
                    [
                        'label' => 'Gii',
                        'icon' => 'file-code-o',
                        'url' => ['/gii']
                    ],
                    [
                        'label' => 'Debug',
                        'icon' => 'dashboard',
                        'url' => ['/debug'],
                    ],
                ],
            ]
        ) ?>

    </section>

</aside>
