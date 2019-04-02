<?php

namespace api\controllers\shop;

use yii\web\NotFoundHttpException;
use yii\rest\Controller;
use yii\data\DataProviderInterface;
use shop\readModels\Shop\TagReadRepository;
use shop\readModels\Shop\ProductReadRepository;
use shop\readModels\Shop\CategoryReadRepository;
use shop\readModels\Shop\BrandReadRepository;
use api\serializers\ProductSerializer;
use api\providers\MapDataProvider;

/**
 * ProductController class serving products
 *
 * @property ProductSerializer $serializers
 * @property ProductReadRepository $products
 * @property CategoryReadRepository $categories
 * @property BrandReadRepository $brands
 * @property TagReadRrepository $tags
 */
class ProductController extends Controller
{
    private $products;
    private $categories;
    private $brands;
    private $tags;
    private $serializers;

    public function __construct(
        $id,
        $module,
        ProductSerializer $serializers,
        ProductReadRepository $products,
        CategoryReadRepository $categories,
        BrandReadRepository $brands,
        TagReadRepository $tags,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->serializers = $serializers;
        $this->products = $products;
        $this->categories = $categories;
        $this->brands = $brands;
        $this->tags = $tags;
    }

    protected function verbs(): array
    {
        return [
            'index' =>    ['GET'],
            'category' => ['GET'],
            'brand' =>    ['GET'],
            'tag' =>      ['GET'],
            'view' =>     ['GET'],
        ];
    }

    /**
     * @return DataProviderInterface
     */
    public function actionIndex(): DataProviderInterface
    {
        $dataProvider = $this->products->getAll();

        return new MapDataProvider($dataProvider, [$this->serializers, 'serializeListItem']);
    }

    /**
     *
     * @param $id
     * @return DataProviderInterface
     * @throws NotFoundHttpException
     */
    public function actionCategory($id): DataProviderInterface
    {
        if (!$category = $this->categories->find($id)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $dataProvider = $this->products->getAllByCategory($category);

        return new MapDataProvider($dataProvider, [$this->serializers, 'serializeListItem']);
    }

    /**
     *
     * @param $id
     * @return DataProviderInterface
     * @throws NotFoundHttpException
     */
    public function actionBrand($id): DataProviderInterface
    {
        if (!$brand = $this->brands->find($id)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $dataProvider = $this->products->getAllByBrand($brand);

        return new MapDataProvider($dataProvider, [$this->serializers, 'serializeListItem']);
    }

    /**
     *
     * @param $id
     * @return DataProviderInterface
     * @throws NotFoundHttpException
     */
    public function actionTag($id): DataProviderInterface
    {
        if (!$tag = $this->tags->find($id)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $dataProvider = $this->products->getAllByTag($tag);

        return new MapDataProvider($dataProvider, [$this->serializers, 'serializeListItem']);
    }

    /**
     *
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionView($id): array
    {
        if (!$product = $this->products->find($id)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $this->serializers->serializeView($product);
    }
}
