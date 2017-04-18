<?php
require __DIR__.'/src/Swoole/ToolKit/AutoReload.php';

$kit = new Swoole\ToolKit\AutoReload(6742);
$kit->watch(__DIR__.'/../link_swoole');
$kit->addFileType('.php');
$kit->run();
