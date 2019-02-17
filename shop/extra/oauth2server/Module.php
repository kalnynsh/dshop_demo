<?php

namespace shop\extra\oauth2server;

use yii\i18n\PhpMessageSource;
use \Yii;

/**
 * For example,
 *
 * ```php
 * 'oauth2' => [
 *     'class' => 'shop\extra\oauth2server\Module',
 *     'tokenParamName' => 'accessToken',
 *     'tokenAccessLifetime' => 3600 * 24,
 *     'storageMap' => [
 *         'user_credentials' => 'common\models\User',
 *     ],
 *     'grantTypes' => [
 *         'user_credentials' => [
 *             'class' => 'OAuth2\GrantType\UserCredentials',
 *         ],
 *         'refresh_token' => [
 *             'class' => 'OAuth2\GrantType\RefreshToken',
 *             'always_issue_new_refresh_token' => true
 *         ]
 *     ]
 * ]
 * ```
 */
class Module extends \yii\base\Module
{
    const VERSION = '2.0.0';

    /**
     * @var array Model's map
     */
    public $modelMap = [];

    /**
     * @var array Storage's map
     */
    public $storageMap = [];

    /**
     * @var array GrantTypes collection
     */
    public $grantTypes = [];

    /**
     * @var string name of access token parameter
     */
    public $tokenParamName;

    /**
     * @var type max access lifetime
     */
    public $tokenAccessLifetime;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    /**
     * Gets Oauth2 Server
     *
     * @return \shop\extra\oauth2server\Server
     * @throws \yii\base\InvalidConfigException
     */
    public function getServer()
    {
        if (!$this->has('server')) {
            $storages = [];

            foreach (array_keys($this->storageMap) as $name) {
                $storages[$name] = \Yii::$container->get($name);
            }

            $grantTypes = [];

            foreach ($this->grantTypes as $name => $options) {
                if (!isset($storages[$name]) || empty($options['class'])) {
                    throw new \yii\base\InvalidConfigException('Invalid grant types configuration.');
                }

                $class = $options['class'];
                unset($options['class']);

                $reflection = new \ReflectionClass($class);
                $config = array_merge([0 => $storages[$name]], [$options]);

                $instance = $reflection->newInstanceArgs($config);
                $grantTypes[$name] = $instance;
            }

            $server = \Yii::$container->get(Server::className(), [
                $this,
                $storages,
                [
                    'token_param_name' => $this->tokenParamName,
                    'access_lifetime' => $this->tokenAccessLifetime,
                ],
                $grantTypes,
            ]);

            $this->set('server', $server);
        }
        return $this->get('server');
    }

    public function getRequest()
    {
        return Request::createFromGlobals();
    }

    public function getResponse()
    {
        return new Response();
    }

    /**
     * Register translations for this module
     *
     * @return array
     */
    public function registerTranslations()
    {
        if (!isset(Yii::$app->get('i18n')->translations['modules/oauth2/*'])) {
            Yii::$app->get('i18n')->translations['modules/oauth2/*'] = [
                'class' => PhpMessageSource::className(),
                'basePath' => __DIR__ . '/messages',
            ];
        }
    }

    /**
     * Translate module message
     *
     * @param string $category
     * @param string $message
     * @param array $params
     * @param string $language
     * @return string
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/oauth2/' . $category, $message, $params, $language);
    }
}
