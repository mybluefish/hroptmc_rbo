<?php
session_start();

require_once 'config/admincfg.php';
require_once 'config/autoload.php';
require_once 'functions/functions.php';

date_default_timezone_set('Asia/Shanghai');
$logRecord = new LoginRecord(date("Y-m-d G:i:s"), $_SESSION["USERNAME"], getIP(), LoginRecord::LOG_OFF_TYPE);
$logRecord->writeRecord();

session_destroy();
echo "Logout Successfully!!<br />";
echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"3; URL=index.php\">";
echo "Wait 3 seconds to redirect to main page, or click <a href=\"index.php\">here</a>";
?>