<?php
session_start();
header('Content-Type: text/html; charset=gb2312');
require_once 'config/admincfg.php';
require_once 'config/autoload.php';
require_once 'functions/functions.php';

date_default_timezone_set('Asia/Shanghai');
$logRecord = new LoginRecord(date("Y-m-d G:i:s"), $_SESSION["USERNAME"], getIP(), LoginRecord::LOG_OFF_TYPE);
$logRecord->writeRecord();

session_destroy();
echo "成功登出！<br />";
echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"3; URL=index.php\">";
echo "3秒钟后自动返回主页，或者点击<a href=\"index.php\">这里</a>返回。";
?>