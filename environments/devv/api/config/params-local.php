<?php

defined('YII_ENV') or define('YII_ENV', 'dev');

defined("API_HOST")
    or define("API_HOST", (YII_ENV === "prod") ? "http://d896.herokuapp.com" : "http://api.dshopv.local");

defined("API_URL")
    or define("API_URL", (YII_ENV === "prod") ? "d896.herokuapp.com" : "api.dshopv.local");

defined("API_TOKEN_URL")
    or define("API_TOKEN_URL", (YII_ENV === "prod") ?
        "http://d896.herokuapp.com/oauth2/token"
        : "http://api.dshopv.local/oauth2/token");

return [];
