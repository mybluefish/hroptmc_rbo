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
echo "�ɹ��ǳ���<br />";
echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"3; URL=index.php\">";
echo "3���Ӻ��Զ�������ҳ�����ߵ��<a href=\"index.php\">����</a>���ء�";
?>