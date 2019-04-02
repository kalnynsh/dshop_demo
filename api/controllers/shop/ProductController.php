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
     * @SWG\Get(
     *     path="/shop/products",
     *     tags={"Catalog"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/ProductItem")
     *         ),
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     *
     * @return DataProviderInterface
     */
    public function actionIndex(): DataProviderInterface
    {
        $dataProvider = $this->products->getAll();

        return new MapDataProvider($dataProvider, [$this->serializers, 'serializeListItem']);
    }

    /**
     * @SWG\Get(
     *     path="/shop/products/category/{categoryId}",
     *     tags={"Catalog"},
     *     @SWG\Parameter(name="categoryId", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/ProductItem")
     *         ),
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
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
     * @SWG\Get(
     *     path="/shop/products/brand/{brandId}",
     *     tags={"Catalog"},
     *     @SWG\Parameter(name="brandId", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/ProductItem")
     *         ),
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
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
     * @SWG\Get(
     *     path="/shop/products/tag/{tagId}",
     *     tags={"Catalog"},
     *     @SWG\Parameter(name="tagId", in="path", required=true, type="integer"),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/ProductItem")
     *         ),
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
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
     * @SWG\Get(
     *     path="/shop/products/{productId}",
     *     tags={"Catalog"},
     *     @SWG\Parameter(
     *         name="productId",
     *         description="ID of product",
     *         in="path",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(ref="#/definitions/ProductView")
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
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

/**
 * @SWG\Definition(
 *     definition="ProductItem",
 *     type="object",
 *     @SWG\Property(property="id", type="integer"),
 *     @SWG\Property(property="code", type="string"),
 *     @SWG\Property(property="name", type="string"),
 *     @SWG\Property(property="category", ref="#/definitions/ProductCategory"),
 *     @SWG\Property(property="brand", ref="#/definitions/ProductBrand"),
 *     @SWG\Property(property="price", ref="#/definitions/ProductPrice"),
 *     @SWG\Property(property="thumbnail", type="string"),
 *     @SWG\Property(property="_links", type="object",
 *         @SWG\Property(property="self", type="object", @SWG\Property(property="href", type="string")),
 *     ),
 * )
 *
 * @SWG\Definition(
 *     definition="ProductView",
 *     type="object",
 *     @SWG\Property(property="id", type="integer"),
 *     @SWG\Property(property="code", type="string"),
 *     @SWG\Property(property="name", type="string"),
 *     @SWG\Property(property="description", type="string"),
 *     @SWG\Property(property="categories", type="object",
 *         @SWG\Property(property="main", ref="#/definitions/ProductCategory"),
 *         @SWG\Property(property="other", type="array", @SWG\Items(ref="#/definitions/ProductCategory")),
 *     ),
 *     @SWG\Property(property="brand", ref="#/definitions/ProductBrand"),
 *     @SWG\Property(property="tags", type="array", @SWG\Items(ref="#/definitions/ProductTag")),
 *     @SWG\Property(property="photos", type="array", @SWG\Items(ref="#/definitions/ProductPhoto")),
 *     @SWG\Property(property="_links", type="object",
 *         @SWG\Property(property="self", type="object", @SWG\Property(property="href", type="string")),
 *     ),
 * )
 *
 * @SWG\Definition(
 *     definition="ProductCategory",
 *     type="object",
 *     @SWG\Property(property="id", type="integer"),
 *     @SWG\Property(property="name", type="string"),
 *     @SWG\Property(property="_links", type="object",
 *         @SWG\Property(property="self", type="object", @SWG\Property(property="href", type="string")),
 *     ),
 * )
 *
 * @SWG\Definition(
 *     definition="ProductBrand",
 *     type="object",
 *     @SWG\Property(property="id", type="integer"),
 *     @SWG\Property(property="name", type="string"),
 *     @SWG\Property(property="_links", type="object",
 *         @SWG\Property(property="self", type="object", @SWG\Property(property="href", type="string")),
 *     ),
 * )
 *
 * @SWG\Definition(
 *     definition="ProductTag",
 *     type="object",
 *     @SWG\Property(property="id", type="integer"),
 *     @SWG\Property(property="name", type="string"),
 *     @SWG\Property(property="_links", type="object",
 *         @SWG\Property(property="self", type="object", @SWG\Property(property="href", type="string")),
 *     ),
 * )
 *
 * @SWG\Definition(
 *     definition="ProductPrice",
 *     type="object",
 *     @SWG\Property(property="new", type="integer"),
 *     @SWG\Property(property="old", type="integer"),
 * )
 *
 * @SWG\Definition(
 *     definition="ProductPhoto",
 *     type="object",
 *     @SWG\Property(property="thumbnail", type="string"),
 *     @SWG\Property(property="origin", type="string"),
 * )
 */
