<?php
ini_set('memory_limit','1024M');    // 临时设置最大内存占用为3G
set_time_limit(0);   // 设置脚本最大执行时间 为0 永不过期 
include './class/onePage.class.php';
include './class/listIndexPage.class.php';

$host = 'http://mm7.zhutixiazai.com';

$listIndexPage = new listIndexPage($host);
$itemArr=['/qcmn/'];
$listIndexPage ->start($itemArr);