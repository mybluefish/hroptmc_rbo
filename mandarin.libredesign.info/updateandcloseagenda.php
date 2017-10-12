<?php
session_start();

if(!isset($_SESSION["USERNAME"])){
	exit;
}

require_once 'functions/functions.php';
require_once 'config/admincfg.php';
require_once 'config/autoload.php';
require_once 'common/servicecommon.php';

define("UPDATE_TYPE", "updatetype");

define("ACTION_UPDATE_TYPE", "update");
define("ACTION_CLOSE_TYPE", "close");
define("ACTION_REOPEN_TYPE", "reopen");

$updateType = $_POST[UPDATE_TYPE];

$sqlLink = MysqlUtil::getInstance();

/**
 *  To check whether it is a officer to prevent injection attack
 */
$officerInstance = Officer::getCurrentOfficer();

if($officerInstance->isOfficer($_SESSION["CLUBID"])){
	exit;
}

if($updateType == ACTION_UPDATE_TYPE){

} elseif ($updatetype == ACTION_CLOSE_TYPE) {
	# code...
} elseif ($updatetype == ACTION_REOPEN_TYPE) {
	# code...
} else {
	exit;
}

?>