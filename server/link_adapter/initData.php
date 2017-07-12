<?php
use Eagle\Service\DbConfig;
use Halo\Data\Remote;
use Halo\Data\DAO;

date_default_timezone_set('Asia/Shanghai');
error_reporting(E_ALL & ~E_NOTICE);

define('APPLICATION_PATH', realpath(__DIR__ . '/../../'));
$classLoader = require(APPLICATION_PATH . '/../../vendor/autoload.php');
$classLoader->setPsr4('Halo\\', APPLICATION_PATH . '/../../library/Halo');
$classLoader->setPsr4('Aliyun\\', APPLICATION_PATH . '/../../library/Aliyun');
$classLoader->setPsr4('Eagle\\', APPLICATION_PATH . '/../../common');
\Halo\Bootstrap::run();

$base= new \Eagle\Service\BaseData();
$base->initPri();

