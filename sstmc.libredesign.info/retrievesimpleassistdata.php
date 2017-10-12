<?php
session_start();

require_once("config/autoload.php");
require_once("config/admincfg.php");

if(!isset($_POST[ConstantValue::CONST_FIELD_TAG_STR])){
	echo ConstantValue::CONST_RETURN_VALUE_ERROR;
	exit;
}

$tag = $_POST[ConstantValue::CONST_FIELD_TAG_STR];

if($tag === ConstantValue::CONST_FIELD_TAG_CLUE_LIST){
	echo TmcClub::getAllValidClubListWithJson();
} elseif ($tag === ConstantValue::CONST_FIELD_TAG_CC_CL_LEVEL){
	if(!isset($_SESSION["USERNAME"])){
		echo ConstantValue::CONST_RETURN_VALUE_ERROR;
		exit;
	}
	
	$clubid = $_POST[ConstantValue::CONST_FIELD_CLUB_ID];
	$typeOfRole = $_POST[ConstantValue::CONST_FIELD_TYPE_OF_ROLE];
	
	$singleMember = Member::getMemberInstanceFromDB($clubid);
	
	$levelName;
	$projectName;
	$levelIndex;
	$projectIndex;
	
	if(RoleNames::isRoleTypeCC($typeOfRole)){
		$levelName = ConstantValue::CONST_FIELD_CC_LEVEL_INDEX;
		$projectName = ConstantValue::CONST_FIELD_CC_PROJECT;
		$levelIndex = $singleMember->getNextLevelCCByIndex();
		$projectIndex = $singleMember->getNextProjectCC();
	} elseif(RoleNames::isRoleTypeCL($typeOfRole)){
		$levelName = ConstantValue::CONST_FIELD_CL_LEVEL_INDEX;
		$projectName = ConstantValue::CONST_FIELD_CL_PROJECT;
		$levelIndex = $singleMember->getNextLevelCLByIndex();
		$projectIndex = $singleMember->getNextProjectCL();
	} else {
		echo ConstantValue::CONST_RETURN_VALUE_ERROR_INFO_PROVIDED;
		exit;
	}
	
	$json_array = array($levelName, $levelIndex, $projectName, $projectIndex);
	
	echo json_encode($json_array);
} elseif ($tag == ConstantValue::CONST_FIELD_TAG_LEVEL_AND_PROJECT_UPDATE){
	if(!isset($_SESSION["USERNAME"])){
		echo ConstantValue::CONST_RETURN_VALUE_ERROR;
		exit;
	}
	
	$clubid = $_POST[ConstantValue::CONST_FIELD_CLUB_ID];
	$typeOfRole = $_POST[ConstantValue::CONST_FIELD_TYPE_OF_ROLE];
	
	$singleMember = Member::getMemberInstanceFromDB($clubid);
	
	$levelName = ConstantValue::CONST_FIELD_LEVEL_PREFIX.$typeOfRole;
	$projectName = ConstantValue::CONST_FIELD_PROJECT_LEVEL_PREFIX.$typeOfRole;
	$levelStr;
	$projectNum;
	
	if(RoleNames::isRoleTypeCC($typeOfRole)){
		$levelStr = $singleMember->getNextLevelCC();
		$projectNum = $singleMember->getNextProjectCC();
	} elseif(RoleNames::isRoleTypeCL($typeOfRole)){
		$levelStr = $singleMember->getNextLevelCL();
		$projectNum = $singleMember->getNextProjectCL();
	} else {
		echo ConstantValue::CONST_RETURN_VALUE_ERROR_INFO_PROVIDED;
		exit;
	}
	
	$json_array = array($levelName, $levelStr, $projectName, $projectNum);
	echo json_encode($json_array);
	
} elseif ($tag == ConstantValue::CONST_FIELD_FETCH_NON_DUPLICATED_ROLES){
	if(!isset($_SESSION["USERNAME"])){
		echo ConstantValue::CONST_RETURN_VALUE_ERROR;
		exit;
	}
	
	$json_array = array();
	
	$roleNamesObject = RoleNames::getInstance();
	
	foreach ($roleNamesObject->nameArray as $roleKey => $roleName){
		if(!RoleNames::isRoleKeysAllowDuplicated($roleKey)){
			$json_array[$roleKey] = $roleName;
		}
	}
	echo json_encode($json_array);
}


?>