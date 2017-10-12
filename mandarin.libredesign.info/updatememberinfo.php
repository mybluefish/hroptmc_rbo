<?php
session_start();

if(!isset($_SESSION["USERNAME"])){
	exit;
}
require_once 'functions/functions.php';
require_once 'config/admincfg.php';
require_once 'config/autoload.php';
require_once 'common/servicecommon.php';


$inputKeys_All = array("editKey", "clubId", "memberId", "name", "validStatus", "cc", "cl", "chineseName", 
		"email", "phoneNo", "qq", "weiboId", "birthday", "pcc", "pcl");

define("CONST_EDITKEY_INDEX", 0);
define("CONST_CLUBID_INDEX", 1);
define("CONST_MEMBERID_INDEX", 2);
define("CONST_NAME_INDEX", 3);
define("CONST_CHINESENAME_INDEX", 7);
define("CONST_EMAIL_INDEX", 8);
define("CONST_PHONENO_INDEX", 9);
define("CONST_QQ_INDEX", 10);
define("CONST_WEIBOID_INDEX", 11);
define("CONST_BIRTHDAY_INDEX", 12);
		
define("CONST_EDIT_KEY_NA", 0);
define("CONST_EDIT_KEY_ONESELF",1);
define("CONST_EDIT_KEY_NORMAL_ADMIN", 2);
define("CONST_EDIT_KEY_SUPER_ADMIN",3);

define("CONST_NA_VALUE", "-");


$editKey = $_POST[$inputKeys_All[CONST_EDITKEY_INDEX]];
$cId = $_POST["cId"];
$trId = $_POST["trId"];
$hiddenId = $_POST["hiddenId"];
$happyBirthdayId = $_POST["happyBirthdayId"];
$sAllMembers = $_POST["sAllMembers"];

if(!isset($editKey) || !isset($cId) || !isset($trId) || !isset($hiddenId) || !isset($happyBirthdayId) || !isset($sAllMembers)){
	exit;
}

$sqlLink = getMysqlConnection(CONST_MYSQL_HOST, CONST_MYSQL_USER_NAME, CONST_MYSQL_PASSWORD, CONST_DB_NAME_HROPTMC);

mysql_query("set names utf8");

$sqlSentenceHead = "UPDATE ".SqlUtil::DB_NAME_MEMBERS." SET ";
$sqlSentenceTail = "WHERE ClubID='".$cId."';";

$valueArrays = array();

if($editKey == CONST_EDIT_KEY_SUPER_ADMIN){
	for($index = CONST_CLUBID_INDEX; $index < count($inputKeys_All); $index++){
		if($_POST[$inputKeys_All[$index]] != CONST_NA_VALUE){
			$valueArrays[$index] = $_POST[$inputKeys_All[$index]];
		}
	}
} elseif($editKey == CONST_EDIT_KEY_NORMAL_ADMIN){
	for($index = CONST_CLUBID_INDEX; $index < count($inputKeys_All); $index++){
		if($index != CONST_CLUBID_INDEX && $index != CONST_MEMBERID_INDEX){
			if($_POST[$inputKeys_All[$index]] != CONST_NA_VALUE){
				$valueArrays[$index] = $_POST[$inputKeys_All[$index]];
			}
		}
	}
} elseif($editKey == CONST_EDIT_KEY_ONESELF){
	for($index = CONST_CLUBID_INDEX; $index < count($inputKeys_All); $index++){
		if($index == CONST_NAME_INDEX || $index == CONST_CHINESENAME_INDEX || $index == CONST_EMAIL_INDEX ||
				$index == CONST_PHONENO_INDEX || $index == CONST_QQ_INDEX || $index == CONST_WEIBOID_INDEX || $index == CONST_BIRTHDAY_INDEX){
			if($_POST[$inputKeys_All[$index]] != CONST_NA_VALUE){
				$valueArrays[$index] = $_POST[$inputKeys_All[$index]];
			}
		}
	}
} else {
	exit;
}

$sqlContent = "";
foreach($valueArrays as $key => $value){
	if($sqlContent != ""){
		$sqlContent .=", ";
	}
	$sqlContent = $sqlContent.$memberTableKeys[$key - 1]."='".$value."'";
}
$sqlContent .=" ";

$sqlSentence = $sqlSentenceHead.$sqlContent.$sqlSentenceTail;

if(mysql_query($sqlSentence, $sqlLink)){
	$singleMember = Member::GetAMember(0, $cId);
	$showAllMembersTag = false;
	if($sAllMembers == "true"){
		$showAllMembersTag = true;
	} elseif($sAllMembers == "false"){
		$showAllMembersTag = false;
	}
	echo $singleMember->getSingleMemberLine($trId, $hiddenId, $editKey, $happyBirthdayId, $showAllMembersTag);
} else {
	echo "Fail";
}
?>