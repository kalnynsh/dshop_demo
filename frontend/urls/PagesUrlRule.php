<?php

namespace frontend\urls;

use yii\web\UrlRuleInterface;
use yii\web\UrlManager;
use yii\web\Request;
use yii\helpers\ArrayHelper;
use yii\caching\Cache;
use yii\base\InvalidParamException;
use yii\base\BaseObject;
use shop\readModels\Content\PageReadRepository;
use shop\entities\Content\Page;

/**
 * PagesUrlRule class
 *
 * @property PageReadRepository $repository
 * @property Cache $cache
 */
class PagesUrlRule extends BaseObject implements UrlRuleInterface
{
    private $repository;
    private $cache;

    public function __construct(
        PageReadRepository $repository,
        Cache $cache,
        $config = []
    ) {
        parent::__construct($config);
        $this->repository = $repository;
        $this->cache = $cache;
    }

    /**
     * parseRequest method
     * Parses the given request and returns the corresponding route and parameters.
     *
     * @param UrlManager $manager the URL manager
     * @param Request $request the request component
     * @return array|bool the parsing result.
     * The route and the parameters are returned as an array.
     * If false, it means this rule cannot be used to parse this path info.
     */
    public function parseRequest($manager, $request)
    {
        $path = $request->pathInfo;

        $result = $this->cache->getOrSet(
            [
                'page_route',
                'path' => $path,
            ],
            function () use ($path) {
                if (!$page = $this->repository->findBySlug(
                    $this->getSlugByPath($path)
                )) {
                    return [
                        'id' => null,
                        'path' => null,
                    ];
                }

                return [
                    'id' => $page->id,
                    'path' => $this->getPagePath($page),
                ];
            }
        );

        if (empty($result['id'])) {
            return false;
        }

        if ($path != $result['path']) {
            throw new UrlNormalizerRedirectException(
                [
                    'page/view',
                    'id' => $result['id']
                ],
                301
            );
        }

        return [
            'page/view',
            [
                'id' => $result['id'],
            ],
        ];
    }

    /**
     * createUrl method creates a URL according
     * to the given route and parameters.
     *
     * @param UrlManager $manager the URL manager
     * @param string $route the route. It should not have slashes at the beginning or the end.
     * @param array $params the parameters
     * @return string|bool the created URL, or false if this rule cannot be used for creating this URL.
     */
    public function createUrl(
        $manager,
        $route,
        $params
    ) {
        if ($route == 'page/view') {
            if (empty($params['id'])) {
                throw new InvalidParamException('Given empty id.');
            }

            $id = $params['id'];

            $url = $this->cache->getOrSet(
                [
                    'page_route',
                    'id' => $id
                ],
                function () use ($id) {
                    if (!$page = $this->repository->find($id)) {
                        return null;
                    }

                    return $this->getPagePath($page);
                }
            );

            if (!$url) {
                throw new InvalidParamException('Given undefind id.');
            }

            unset($params['id']);

            if (!empty($params)
                && ($query = http_build_query($params)) !== ''
            ) {
                $url .= '?' . $query;
            }

            return $url;
        }

        return false;
    }

    private function getSlugByPath($path): string
    {
        $chunks = explode('/', $path);

        return end($chunks);
    }

    private function getPagePath(Page $page): string
    {
        $chunks = ArrayHelper::getColumn(
            $page->getParents()->andWhere(['>', 'depth', 0])->all(),
            'slug'
        );
        $chunks[] = $page->slug;

        return implode('/', $chunks);
    }
}
