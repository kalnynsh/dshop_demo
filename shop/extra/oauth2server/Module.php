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
 *         'user_credentials' => 'common\auth\Identity',
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
     * @var boolean whether to use JWT tokens
     */
    public $useJwtToken = false;

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
        if (!$this->has('oauth2server', true)) {
            $storages = [];

            if ($this->useJwtToken) {
                $this->setJwtTokenKeys();
            }

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

            $server = \Yii::$container->get(Server::class, [
                $this,
                $storages,
                array_merge(array_filter([
                    'use_jwt_access_tokens' => $this->useJwtToken,
                    'token_param_name' => $this->tokenParamName,
                    'access_lifetime' => $this->tokenAccessLifetime,
                ]), $options),
                $grantTypes,
            ]);

            $this->set('oauth2server', $server);
        }

        return $this->get('oauth2server');
    }

    public function getRequest()
    {
        if (!$this->has('oauth2request', true)) {
            $this->set('oauth2request', Request::createFromGlobals());
        }

        return $this->get('oauth2request');
    }

    public function getResponse()
    {
        if (!$this->has('oauth2response', true)) {
            $this->set('oauth2response', new Response());
        }

        return $this->get('oauth2response');
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
                'class' => PhpMessageSource::class,
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

    private function setJwtTokenKeys(): void
    {
        if (!array_key_exists('access_token', $this->storageMap)
            || !array_key_exists('public_key', $this->storageMap)
        ) {
            throw new \yii\base\InvalidConfigException(
                'access_token and public_key must be set or set useJwtToken to false'
            );
        }

        // define dependencies when JWT is used instead of normal token
        \Yii::$container->clear('public_key');
        \Yii::$container->set('public_key', $this->strageMap['public_key']);
        \Yii::$container->set('OAuth\Storage\PublicKeyInterface', $this->strageMap['public_key']);

        \Yii::$container->clear('access_token');
        \Yii::$container->set('access_token', $this->strageMap['access_token']);
    }
}
