<?php
	session_start();
	date_default_timezone_set('Asia/Shanghai');
	header('Content-Type: text/html; charset=utf-8');
	mysql_query("SET NAMES UTF8");
	if(isset($_SESSION["USERNAME"])){
		echo "欢迎回来，".$_SESSION["MEMBERNAME"]."！<br />";
		echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=".(isset($_POST["backToFileName"]) ? $_POST["backToFileName"] : "index.php")."\">";
		echo "1秒钟后自动返回主页，或者点击<a href=\"".(isset($_POST["backToFileName"]) ? $_POST["backToFileName"] : "index.php")."\">这里</a>返回。";
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
	mysql_query("SET NAMES UTF8");

	if($actionId == ACTION_LOGIN_KEY){
		$loginUserName = $_POST[USERNAME_PREFIX."_".$actionId];
		$passwordHash = $_POST[PASSWORD_MD5_NAME."_".$actionId];

		$tempSqlSentence = "select * from ".SqlUtil::DB_NAME_USERS." where username='".$loginUserName."';";
		$tempSqlResult = mysql_query($tempSqlSentence, $sqlLink) or die("Fail to execute query");
		if(mysql_num_rows($tempSqlResult) == 0){
			echo "该用户名 \"".$loginUserName."\" 未找到，请检查！<br />";
			echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=".$backToFileName."\">";
			echo "1秒钟自动跳转或点击<a href=\"".$backToFileName."\">这里</a>重新登录。";
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

				echo "登录成功！欢迎回来".$_SESSION["MEMBERNAME"]."！<br />";
				echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"3; URL=".(strpos($backToFileName, "login.php") !== false ? "index.php" : $backToFileName)."\">";
				echo "3秒钟后自动返回上一页，或者点击<a href=\"".(strpos($backToFileName, "login.php") !== false ? "index.php" : $backToFileName)."\">这里</a>返回。";
			} else {
				echoSqlConnectionErrors();
			}
		} else {
			echo "密码错误！<br />";
			echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"3; URL=".$backToFileName."\">";
			echo "3秒钟后自动返回上一页，或者点击<a href=\"".$backToFileName."\">这里</a>返回。";
		}
	} elseif ($actionId = ACTION_REG_KEY){
		if(strpos($_POST[CLUB_ID_NAME], "_") === false){
			echo "您输入的会员编号格式有误，请输入正确的格式！！<br / >";
			echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"3; URL=".$backToFileName."\">";
			echo "点击<a href='".$backToFileName."'>这里</a>回到注册页面以使用其他会员编号注册。ᡣ";
			exit;
		}
		$regClubIdArray = explode("_", $_POST[CLUB_ID_NAME]);
		$regClubId = $regClubIdArray[0];
		$loginUserName = $_POST[USERNAME_PREFIX."_".$actionId];
		$passwordHash = $_POST[PASSWORD_MD5_NAME."_".$actionId];

		if(SqlUtil::getFetchObjectByGivenField(SqlUtil::USERS_FIELD_NAME_CLUBID, $regClubId, SqlUtil::DB_NAME_USERS, $sqlLink)){
			echo "该会员编号已经被注册过。<br />会员编号：".$regClubId."<br />";
			echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"3; URL=".$backToFileName."\">";
			echo "点击<a href='".$backToFileName."'>这里</a>回到注册页面以使用其他会员编号注册。";
			exit;
		} elseif(SqlUtil::getFetchObjectByGivenField(SqlUtil::USERS_FIELD_NAME_USERNAME, $loginUserName, SqlUtil::DB_NAME_USERS, $sqlLink)) {
			echo "该用户名已经存在。<br />会员编号：".$loginUserName."<br />";
			echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"3; URL=".$backToFileName."\">";
			echo "点击<a href='".$backToFileName."'>这里</a>回到注册页面以使用其他会员编号注册。";
			exit;
		} else {
			if(Member::isMemberExist($regClubId, $sqlLink)){
				$memberObject = Member::GetAMember(0, $regClubId);
				if(!Member::isGivenIdValidMember($regClubId, $sqlLink)){
					echo "该会员编号（".$regClubId."）因为未续费已经不是有效会员，请联系会员副主席为该会员续费。<br />";
					echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"3; URL=".$backToFileName."\">";
					echo "点击<a href='".$backToFileName."'>这里</a>回到注册页面以使用其他会员编号注册。";
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

						echo "注册成功，欢迎加入我们，".$_SESSION["MEMBERNAME"]."！<br />";
						echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=".((strpos($backToFileName, "register.php") !== false ? "index.php" : $backToFileName))."\">";
						echo "1秒钟后自动返回上一页，或者点击<a href=\"".((strpos($backToFileName, "register.php") !== false ? "index.php" : $backToFileName))."\">这里</a>返回。";
					} else {
						echoSqlConnectionErrors();
					}
				}
			} else {
				echo "该会员编号（".$regClubId."）无效，想成为会员，请联系会员副主席。";
				echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"3; URL=".$backToFileName."\">";
				echo "点击<a href='".$backToFileName."'>这里</a>回到注册页面以使用其他会员编号注册。";
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
					echo "您的管理员登记已经从".$currentAdminLevel."更新至".$targetAdminLevel."。<br />";
				}
			}
		}
	}
?>
