<?php

$yii=dirname(__FILE__).'/yii/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';
date_default_timezone_set('Asia/Kolkata');

defined('YII_DEBUG') or define('YII_DEBUG',true);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once(dirname(__FILE__).'/protected/vendor/DJJob.php');

require_once($yii);
Yii::createWebApplication($config)->run();
