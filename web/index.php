<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

// TODO Разобраться с PSR-4 и автозагрузкой классов
require __DIR__ . '/../components/AbstractProvider.php';
require __DIR__ . '/../components/Database/DatabaseProvider.php';
require __DIR__ . '/../components/Database/models/TaskDB.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
