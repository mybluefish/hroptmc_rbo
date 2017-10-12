<?php
	require_once("common/servicecommon.php");
	require_once 'config/autoload.php';

	$meetingDate = $_POST["meetingdate"];
	$keyRoleName = $_POST["keyrolename"];
	$transferValue = $_POST["transferValue"];

	$inputKey = isset($_POST["inputkey"])?$_POST["inputkey"]:null;

	$themeValue;

	$levelindex;
	$project;
	$guestLevel;
	$guestClubIndex;
	$phoneNumber;
	$email;


	if(isset($_POST["themeValue"])){
		$themeValue = $_POST["themeValue"];
	}

	if(isset($_POST["levelindex"]) && isset($_POST["project"])){
		$levelindex = $_POST["levelindex"];
		$project = $_POST["project"];
	}

	if(($inputKey != null) && ($inputKey == 2)){
		$guestLevel = $_POST["guestlevel"];
		$guestClubIndex = $_POST["clubindex"];
		$phoneNumber = $_POST["phonenumber"];
		$email = $_POST["email"];
	}

	$CC = array("CC", "ACB", "ACS", "ACG", "DTM");


	// Error code used in case of different failures
	$OK_CODE = 0;
	$ERROR_CODE_ALREADY_TAKEN = 1;
	$ERROR_CODE_NO_SUCH_USER_EXIT = 2;
	$ERROR_CODE_NOT_VALID_USER = 3;
	$ERROR_CODE_MYSQL_QUERY_FAIL = 4;
	$ERROR_CODE_RE_PARTICIPATE = 5;
	$WARNING_CODE_THEME_ALREADY_REG_OUTSIDE_TTM = 6;
	$ERROR_CODE_RE_PARTICIPATE_GUEST = 7;

	$thisRegularMeeting = new RegularMeeting(RoleNames::getInstance(), MeetingDate::getMeetingDateFromTimeStamp(strtotime($meetingDate)), true);

	$mysqlUtil = MysqlUtil::getInstance();

	// To check that whether current role is taken by someone else
	$checkSql = "SELECT ".$keyRoleName." FROM ".RegularMeeting::CONST_TABLE_NAME." WHERE ".RegularMeeting::CONST_FIELD_DATE."='".$meetingDate."';";
	mysql_query("SET NAMES UTF8");
	$sqlResult = $mysqlUtil->queryGetResult($checkSql);

	$fetchedObject = $mysqlUtil->getQueryResultObject($sqlResult);

	if(($fetchedObject->{$keyRoleName} != "") || ($fetchedObject->{$keyRoleName} != null)){
		echo $ERROR_CODE_ALREADY_TAKEN;
		exit;
	}


	$sqlSentence = "";
	$sqlSentence2 = "";

	//Trying to get club id with inputkey and inputvalue
	$clubId = 0;

	if(!RoleNames::isTheme($keyRoleName)){
		if($inputKey == 0){
			$clubId = $transferValue;
		} elseif($inputKey == 1){
			$clubId = Member::getClubIDWithMemberName($transferValue);

			if($clubId === false){
				echo $ERROR_CODE_NO_SUCH_USER_EXIT;
				exit;
			}
		}
	}

	if(RoleNames::isTheme($keyRoleName)){
		$sqlSentence = "UPDATE ".RegularMeeting::CONST_TABLE_NAME." SET ".$keyRoleName."='".$transferValue."' WHERE ".RegularMeeting::CONST_FIELD_DATE."='".$meetingDate."';";
	} elseif($inputKey != 2){

		if((!RoleNames::isRoleKeysAllowDuplicated($keyRoleName)) && RoleNames::isDuplicatedRoleSubmitted($meetingDate, $clubId)){
			echo $ERROR_CODE_RE_PARTICIPATE;
			exit;
		} else {
			$singleMember = Member::getMemberInstanceFromDB($clubId);

			if($singleMember){
				if($singleMember->isValidMember()){

					$tempFieldContentObject = FieldContentInAgenda::getFieldContentInAgendaFromInfoProvided($meetingDate, $keyRoleName);
					$tempFieldContentObject->setMember(true);
					$tempFieldContentObject->setId($clubId);
					$tempFieldContentObject->setMemberName($singleMember->MemberName);
					$tempFieldContentObject->setMemberLevelInCurrent($singleMember->getMemberLevel());
					$tempFieldContentObject->setEmpty(false);

					if(RoleNames::isRoleTypeCCOrCL($keyRoleName)){
						$tempFieldContentObject->setCurrentRoleLevel(RoleNames::isRoleTypeCC($keyRoleName)?Member::$CC[$levelindex]:Member::$CL[$levelindex]);
						$tempFieldContentObject->setProjectNumber($project);
					}

					$sqlSentence = "UPDATE  `".SqlCfg::CONST_DB_NAME_HROPTMC."`.`".RegularMeeting::CONST_TABLE_NAME."` SET  `".$keyRoleName."` =  '".$tempFieldContentObject->getDatabaseFormat()."'
 						WHERE CONVERT(  `".RegularMeeting::CONST_TABLE_NAME."`.`".RegularMeeting::CONST_FIELD_DATE."` USING utf8 ) =  '".$meetingDate."' LIMIT 1 ;";

					if(RoleNames::isTme($keyRoleName)){
						if(isset($themeValue)){
							$themeValue = trim($themeValue);
							if(!empty($themeValue)){
								$sqlSentence2 = "UPDATE  `".SqlCfg::CONST_DB_NAME_HROPTMC."`.`".RegularMeeting::CONST_TABLE_NAME."` SET  `".RoleNames::CONST_VALUE_THEME."` =  '".$themeValue."'
	 								WHERE CONVERT(  `".RegularMeeting::CONST_TABLE_NAME."`.`".RegularMeeting::CONST_FIELD_DATE."` USING utf8 ) =  '".$meetingDate."' LIMIT 1 ;";
							}
						}
/*
						if(isset($themeValue) && !empty(trim($themeValue))){
						    $themeValue = trim($themeValue);
							$sqlSentence2 = "UPDATE  `".SqlCfg::CONST_DB_NAME_HROPTMC."`.`".RegularMeeting::CONST_TABLE_NAME."` SET  `".RoleNames::CONST_VALUE_THEME."` =  '".$themeValue."'
 								WHERE CONVERT(  `".RegularMeeting::CONST_TABLE_NAME."`.`".RegularMeeting::CONST_FIELD_DATE."` USING utf8 ) =  '".$meetingDate."' LIMIT 1 ;";
						}*/
					}
				} else {
					echo $ERROR_CODE_NOT_VALID_USER;
					exit;
				}
			} else {
				echo $ERROR_CODE_NO_SUCH_USER_EXIT;
				exit;
			}
		}

	} else {
		$newGuest = Guest::getGuestWithIdenticalInfo($transferValue, $guestClubIndex, $phoneNumber, $email, $meetingDate);
		if($newGuest){
			$newGuest->updateLastActivityDateUpToDate();
		}
		if((!RoleNames::isRoleKeysAllowDuplicated($keyRoleName)) && $thisRegularMeeting->isGuestRegistered($newGuest)){
			echo $ERROR_CODE_RE_PARTICIPATE_GUEST;
			exit;
		} else {
			$tempFieldContentObject = FieldContentInAgenda::getFieldContentInAgendaFromInfoProvided($meetingDate, $keyRoleName);
			$tempFieldContentObject->setEmpty(false);
			$tempFieldContentObject->setMember(false);
			$tempFieldContentObject->setId($newGuest->getGuestId());
			$tempFieldContentObject->setClubNameAndIndexWithClubIndexForNonMember($guestClubIndex);
			$tempFieldContentObject->setMemberName($transferValue);
			$tempFieldContentObject->setMemberLevelInCurrent($guestLevel);

			if(RoleNames::isRoleTypeCCOrCL($keyRoleName)){
				$tempFieldContentObject->setCurrentRoleLevel(RoleNames::isRoleTypeCC($keyRoleName)?Member::$CC[$levelindex]:Member::$CL[$levelindex]);
				$tempFieldContentObject->setProjectNumber($project);
			}

			$newGuest->updateLastRegDateUpToDate();

			$sqlSentence = "UPDATE  `".SqlCfg::CONST_DB_NAME_HROPTMC."`.`".RegularMeeting::CONST_TABLE_NAME."` SET  `".$keyRoleName."` =  '".$tempFieldContentObject->getDatabaseFormat()."'
 						WHERE CONVERT(  `".RegularMeeting::CONST_TABLE_NAME."`.`".RegularMeeting::CONST_FIELD_DATE."` USING utf8 ) =  '".$meetingDate."' LIMIT 1 ;";

			if(RoleNames::isTme($keyRoleName)){
				if(isset($themeValue)){
					$sqlSentence2 = "UPDATE  `".SqlCfg::CONST_DB_NAME_HROPTMC."`.`".RegularMeeting::CONST_TABLE_NAME."` SET  `".RoleNames::CONST_VALUE_THEME."` =  '".$themeValue."'
 								WHERE CONVERT(  `".RegularMeeting::CONST_TABLE_NAME."`.`".RegularMeeting::CONST_FIELD_DATE."` USING utf8 ) =  '".$meetingDate."' LIMIT 1 ;";
				}
			}
		}
	}
	mysql_query("SET NAMES UTF8");
	if(($sqlSentence != "") && $mysqlUtil->queryGetResult($sqlSentence)){
		if($sqlSentence2 == ""){
			echo $OK_CODE;
			exit;
		} else {
			// To check that whether current role is taken by someone else
			$checkSql = "SELECT ".RoleNames::CONST_VALUE_THEME." FROM ".RegularMeeting::CONST_TABLE_NAME." WHERE ".RegularMeeting::CONST_FIELD_DATE."='".$meetingDate."';";
			mysql_query("SET NAMES UTF8");
			$sqlResult = $mysqlUtil->queryGetResult($checkSql);

			$sqlObject;

			if($mysqlUtil->getQueryRowNum($sqlResult) != 0){
				$sqlObject = $mysqlUtil->getQueryResultObject($sqlResult);
			}

			if(isset($sqlObject) && ($sqlObject->theme != "") && ($sqlObject->theme) != null){
				echo $WARNING_CODE_THEME_ALREADY_REG_OUTSIDE_TTM;
				exit;
			}

			if($mysqlUtil->queryGetResult($sqlSentence2)){
				echo $OK_CODE;
				exit;
			} else {
				echo $ERROR_CODE_MYSQL_QUERY_FAIL;
				exit;
			}
		}
	} else {
		echo $ERROR_CODE_MYSQL_QUERY_FAIL;
		exit;
	}

?>
