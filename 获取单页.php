<?php

include './class/onePage.class.php';

$host = 'http://mm7.zhutixiazai.com';
$dirName = '/rhmn/';
$code = '650';

$oneInfo = new oneInfo($host,$dirName,$code);
$oneInfo ->start();






