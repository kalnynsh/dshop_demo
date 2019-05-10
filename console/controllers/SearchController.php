<?php

namespace console\controllers;

use yii\console\Controller;
use shop\services\search\ProductIndexer;
use shop\entities\Shop\Product\Product;

class SearchController extends Controller
{
    private $indexer;

    public function __construct(
        $id,
        $module,
        ProductIndexer $indexer,
        $config = []
    ) {
        parent::__construct($id, $module, $config);

        $this->indexer = $indexer;
    }

    public function actionReindex(): void
    {
        $query = Product::find()
            ->active()
            ->with([
                'category',
                'categoryAssignments',
                'tagAssignments',
                'values'
            ])
            ->orderBy('id');

        $this->stdout('Clearing' . PHP_EOL);
        $this->indexer->clear();

        $this->stdout('Product indexing' . PHP_EOL);

        foreach ($query->each() as $product) {
            /** @var Product $product */
            $this->sdtout('Product #' . $product->id . PHP_EOL);
            $this->indexer->index($product);
        }

        $this->stdout('Done!' . PHP_EOL);
    }
}
