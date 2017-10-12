<?php
	session_start();
	header('Content-Type: text/html; charset=utf-8');
	
	if(isset($_SESSION["USERNAME"])){
		echo "Welcome back ".$_SESSION["MEMBERNAME"]."!<br />";
		echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=".(isset($_POST["backToFileName"]) ? $_POST["backToFileName"] : "index.php")."\">";
		echo "Wait 1 seconds to redirect to main page, or click <a href=\"".(isset($_POST["backToFileName"]) ? $_POST["backToFileName"] : "index.php")."\">here</a>";
		exit;
	}
	
	require_once 'config/admincfg.php';
	require_once 'config/autoload.php';
	require_once 'functions/functions.php';
	require_once 'common/servicecommon.php';
	
	define("ACTION_LOGIN_KEY", "login");
	define("ACTION_REG_KEY", "register");
	
	define("ACTION_ID_NAME", "actionId");
	define("BACKTO_FILENAME", "backToFileName");
	define("USERNAME_PREFIX", "userName");
	define("PASSWORD_MD5_NAME", "passwordMD5");
	define("CLUB_ID_NAME", "clubId");
	
	$actionId = $_POST[ACTION_ID_NAME];
	$backToFileName = $_POST[BACKTO_FILENAME];


	$sqlLink = mysql_connect(CONST_MYSQL_HOST, CONST_MYSQL_USER_NAME, CONST_MYSQL_PASSWORD) or die("ERROR: Fail to connect to mysql
				database. At line ".__LINE__." in file ".__FILE__."<br>");
	
	mysql_select_db(CONST_DB_NAME_HROPTMC, $sqlLink) or die("ERROR: Fail to select the database. At line ".__LINE__."
				in file ".__FILE__."<br>");
	
	if($actionId == ACTION_LOGIN_KEY){
		$loginUserName = $_POST[USERNAME_PREFIX."_".$actionId];
		$passwordHash = $_POST[PASSWORD_MD5_NAME."_".$actionId];
		
		$tempSqlSentence = "select * from ".SqlUtil::DB_NAME_USERS." where username='".$loginUserName."';";
		$tempSqlResult = mysql_query($tempSqlSentence, $sqlLink) or die("Fail to execute query");
		if(mysql_num_rows($tempSqlResult) == 0){
			echo "No user named: \"".$loginUserName."\" found! Please Check that!<br />";
			echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=".$backToFileName."\">";
			echo "Wait 1 seconds or click <a href=\"".$backToFileName."\">here</a> to login again";
			exit;
		}
		$tempSqlQueryObject = mysql_fetch_object($tempSqlResult);
		if($tempSqlQueryObject->pswd === $passwordHash){
			$_SESSION["USERNAME"] = $loginUserName;
			if($tempSqlQueryObject = SqlUtil::getFetchObjectByGivenField(SqlUtil::MEMBERS_FIELD_NAME_CLUBID, $tempSqlQueryObject->clubid, SqlUtil::DB_NAME_MEMBERS, $sqlLink)){
				$_SESSION["CLUBID"] = $tempSqlQueryObject->ClubID;
				$_SESSION["MEMBERNAME"] = $tempSqlQueryObject->MemberName;
				
				setAdminLevel($_SESSION["CLUBID"], $sqlLink);
				
				date_default_timezone_set('Asia/Shanghai');
				$logRecord = new LoginRecord(date("Y-m-d G:i:s"), $_SESSION["USERNAME"], getIP(), LoginRecord::LOG_ON_TYPE);
				$logRecord->writeRecord();
				
				echo "Login Successfully!! Welcome back ".$_SESSION["MEMBERNAME"]."!<br />";
				echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"3; URL=".(strpos($backToFileName, "login.php") !== false ? "index.php" : $backToFileName)."\">";
				echo "Wait 3 seconds to redirect to go back, or click <a href=\"".(strpos($backToFileName, "login.php") !== false ? "index.php" : $backToFileName)."\">here</a>";
			} else {
				echoSqlConnectionErrors();
			}
		} else {
			echo "Password not correct!<br />";
			echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"3; URL=".$backToFileName."\">";
			echo "Wait 3 seconds to redirect to go back, or click <a href=\"".$backToFileName."\">here</a>";
		}
	} elseif ($actionId = ACTION_REG_KEY){
		if(strpos($_POST[CLUB_ID_NAME], "_") === false){
			echo "Wrong format of club id is input, please input it in correct format!!<br / >";
			echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"3; URL=".$backToFileName."\">";
			echo "Click <a href='".$backToFileName."'>here</a> to go back to registeration page to register for another club id.";
			exit;
		}
		$regClubIdArray = explode("_", $_POST[CLUB_ID_NAME]);
		$regClubId = $regClubIdArray[0];
		$loginUserName = $_POST[USERNAME_PREFIX."_".$actionId];
		$passwordHash = $_POST[PASSWORD_MD5_NAME."_".$actionId];
		
		if(SqlUtil::getFetchObjectByGivenField(SqlUtil::USERS_FIELD_NAME_CLUBID, $regClubId, SqlUtil::DB_NAME_USERS, $sqlLink)){
			echo "This club id is already registered!!<br />Club ID: ".$regClubId."<br />";
			echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"3; URL=".$backToFileName."\">";
			echo "Click <a href='".$backToFileName."'>here</a> to go back to registeration page to register for another club id.";
			exit;
		} elseif(SqlUtil::getFetchObjectByGivenField(SqlUtil::USERS_FIELD_NAME_USERNAME, $loginUserName, SqlUtil::DB_NAME_USERS, $sqlLink)) {
			echo "This login name is already used by another member!!<br />Login Name: ".$loginUserName."<br />";
			echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"3; URL=".$backToFileName."\">";
			echo "Click <a href='".$backToFileName."'>here</a> to go back to registeration page to choose another login name.";
			exit;
		} else {
			if(Member::isMemberExist($regClubId, $sqlLink)){
				$memberObject = Member::GetAMember(0, $regClubId);
				if(!Member::isGivenIdValidMember($regClubId, $sqlLink)){
					echo "This Club ID(".$regClubId.") is no longer valid in our club, please contact VPM(Jerry, tojiangkun@qq.com) to resume it.<br />";
					echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"3; URL=".$backToFileName."\">";
					echo "Click <a href='".$backToFileName."'>here</a> to go back to registeration page to register for another club id.";
					exit;
				} else {
					$tempSqlSentence = "INSERT INTO  `".CONST_DB_NAME_HROPTMC."`.`".SqlUtil::DB_NAME_USERS."` 
							(`clubid` ,`username` ,`pswd` ,`onetimeurl`)VALUES ('".$regClubId."',  '".$loginUserName."',  '".$passwordHash."', NULL);";
					if(mysql_query($tempSqlSentence, $sqlLink)){
						$_SESSION["USERNAME"] =  $loginUserName;
						$_SESSION["CLUBID"] = $memberObject->ClubID;
						$_SESSION["MEMBERNAME"] = $memberObject->MemberName;
						
						setAdminLevel($_SESSION["CLUBID"], $sqlLink);
						
						date_default_timezone_set('Asia/Shanghai');
						$logRecord = new LoginRecord(date("Y-m-d G:i:s"), $_SESSION["USERNAME"], getIP(), LoginRecord::LOG_ON_TYPE);
						$logRecord->writeRecord();
						
						echo "User registered successfully. Welcome to join us, ".$_SESSION["MEMBERNAME"]."!<br />";
						echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=".((strpos($backToFileName, "register.php") !== false ? "index.php" : $backToFileName))."\">";
						echo "Wait 1 seconds to redirect to go back, or click <a href=\"".((strpos($backToFileName, "register.php") !== false ? "index.php" : $backToFileName))."\">here</a>";
					} else {
						echoSqlConnectionErrors();
					}
				}
			} else {
				echo "This Club ID(".$regClubId.") does not exist in our club, please contact VPM(Jerry, tojiangkun@qq.com) to be our member!";
				echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"3; URL=".$backToFileName."\">";
				echo "Click <a href='".$backToFileName."'>here</a> to go back to registeration page to register for another club id.";
				exit;
			}
		}
	}
	
	
	function setAdminLevel($clubId, $link){
		$targetAdminLevel = RegUser::caculateCurrentAdminLevel($clubId);
		if($tempSqlQueryObject = SqlUtil::getFetchObjectByGivenField(SqlUtil::USERS_FIELD_NAME_CLUBID, $clubId, SqlUtil::DB_NAME_USERS, $link)){
			$currentAdminLevel = $tempSqlQueryObject->{SqlUtil::USERS_FIELD_NAME_ADMINLEVEL};
			$_SESSION["ADMINLEVEL"] = $currentAdminLevel;
			
			if($targetAdminLevel != $currentAdminLevel){
				if(SqlUtil::updateDatabaseByClubID(array(SqlUtil::USERS_FIELD_NAME_ADMINLEVEL => $targetAdminLevel), SqlUtil::USERS_FIELD_NAME_CLUBID, $clubId, SqlUtil::DB_NAME_USERS, $link)){
					echo "Your admin level has update from ".$currentAdminLevel." to ".$targetAdminLevel."<br />";
				}
			}
		}
	}
?>