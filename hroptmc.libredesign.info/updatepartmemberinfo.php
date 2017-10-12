<?php 
session_start();

if(!isset($_SESSION["USERNAME"])){
	exit;
}
if(!isset($_POST["updateTag"])){
	exit;
}

$updateTag =  $_POST["updateTag"];
$sAllMembers = $_POST["sAllMembers"];

require_once 'functions/functions.php';
require_once 'config/admincfg.php';
require_once 'config/autoload.php';
require_once 'common/servicecommon.php';

$memberMgmt = MemberManager::getInstance($_SESSION["CLUBID"], "memberContainer");

if($sAllMembers == "true"){
	$memberMgmt->setShowMembersOption(true);
} elseif($sAllMembers == "false"){
	$memberMgmt->setShowMembersOption(false);
}

if($updateTag == "birthday"){
	$memberMgmt->getHappyBirthdayContent();
} elseif($updateTag == "All"){
	$memberMgmt->drawToPage("membersTable", "memberHeader", "memberLine", "memberInvalid", "happyBirthday");
}
?>

