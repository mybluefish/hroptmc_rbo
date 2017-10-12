<?php
	session_start();
	header('Content-Type: text/html; charset=utf-8');
	mysql_query("set names utf8");
	date_default_timezone_set('Asia/Shanghai');
	
	if(!isset($_SESSION["USERNAME"])){
		echo "You are not authorized to access this function!<br />";
		echo "Please to check whether you are logged out, try log in again!!<br />";
		echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"3; URL=manageagendas.php\">";
		echo "Wait 3 seconds to redirect to manage agenda, or click <a href=\"manageagendas.php\">here</a>";
		exit;
	}
	
	require_once 'config/autoload.php';
	require_once 'config/admincfg.php';
	
	$meetingDateString = $_POST[ConstantValue::CONST_MEETING_DATE_HIDDEN_NAME];
	
	$roleKeyToContentArray = array();
	
	$roleNamesObject = RoleNames::getInstance();
	
	foreach ($roleNamesObject->nameArray as $roleKey => $roleName){
		if(!RoleNames::isPresident($roleKey)){
			if(RoleNames::isTheme($roleKey)){
				$themeString = $_POST[ConstantValue::CONST_FIELD_THEME_NAME];
				$themeString = trim($themeString);
				if(isset($themeString) && (!empty($themeString))){
					$roleKeyToContentArray[$roleKey] = $themeString;
				}
			} else {
				//if checkbox is not selected, no value will be transferred to here, so if it is not set, means members are chosen
				if(isset($_POST[ConstantValue::CONST_FIELD_CHECKBOX_CHOOSE_NON_MEMBER_PREFIX.ConstantValue::CONST_FIELD_NAME_MIDFIX.$roleKey])){
					// non-members
				} else {
					// Members are handled here
					$clubId = $_POST[ConstantValue::CONST_FIELD_ROLE_NAME_TO_BE_CHOOSEN_PREFIX.ConstantValue::CONST_FIELD_NAME_MIDFIX.$roleKey];
					
					if($clubId != ConstantValue::CONST_FIELD_CLUB_ID_NOT_VALID ){
						
						$memberObj = Member::getMemberInstanceFromDB($clubId);
						
						$tempFieldContentObject = FieldContentInAgenda::getFieldContentInAgendaFromInfoProvided($meetingDateString, $roleKey);
						$tempFieldContentObject->setMember(true);
						$tempFieldContentObject->setId($clubId);
						$tempFieldContentObject->setMemberName($memberObj->MemberName);
						$tempFieldContentObject->setMemberLevelInCurrent($memberObj->getMemberLevel());
						$tempFieldContentObject->setEmpty(false);
						
						if(RoleNames::isRoleTypeCCOrCL($roleKey)){
							$level = $_POST[ConstantValue::CONST_FIELD_LEVEL_PREFIX.ConstantValue::CONST_FIELD_NAME_MIDFIX.$roleKey];
							$project = $_POST[ConstantValue::CONST_FIELD_PROJECT_LEVEL_PREFIX.ConstantValue::CONST_FIELD_NAME_MIDFIX.$roleKey];
							$tempFieldContentObject->setCurrentRoleLevel($level);
							$tempFieldContentObject->setProjectNumber($project);
						}
						
						$roleKeyToContentArray[$roleKey] = $tempFieldContentObject->getDatabaseFormat();
					} else {
						$roleKeyToContentArray[$roleKey] = NULL;
					}
				}
			}
		}
	}
	
	$sqlSentenceHead = "UPDATE  `".CONST_DB_NAME_HROPTMC."`.`".RegularMeeting::CONST_TABLE_NAME."` SET ";
	$sqlSentenceBody = "";
	$sqlSentenceTail = " WHERE CONVERT(  `".RegularMeeting::CONST_TABLE_NAME."`.`".RegularMeeting::CONST_FIELD_DATE."` USING utf8 ) =  '".$meetingDateString."' LIMIT 1 ;";
	
	$length = count($roleKeyToContentArray);
	$i = 0;
	foreach ($roleKeyToContentArray as $key => $name){
		$i++;
		$sqlSentenceBody .= "`".$key."`='".$name."'";
		if($i != $length){
			$sqlSentenceBody.=", ";
		}
	}
	
	$sqlSentence = $sqlSentenceHead.$sqlSentenceBody.$sqlSentenceTail;
	
	$mysqlUtil = MysqlUtil::getInstance();
	
	if($mysqlUtil->queryGetResult($sqlSentence)){
		echo "Congratulations!!<br />";
		echo "Agenda is sucessfully updated!!<br />";
		echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"3; URL=manageagendas.php\">";
		echo "Wait 3 seconds to redirect to manage agenda, or click <a href=\"manageagendas.php\">here</a>";
	} else {
		echo "Fail to update agenda!<br />";
		echo "Please check your have the right and submit it again!!<br />";
		echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"3; URL=manageagendas.php\">";
		echo "Wait 3 seconds to redirect to manage agenda, or click <a href=\"manageagendas.php\">here</a>";
	}
?>