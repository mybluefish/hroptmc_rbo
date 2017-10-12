<?php

	require_once("common/servicecommon.php");
	require_once 'classes/sqlutil.php';
	require_once 'classes/sqlcfg.php';

	$meetingDate = $_POST["meetingdate"];
	$keyOfRoleName = $_POST["rolename"];

	$sqlLink = mysql_connect(SqlCfg::CONST_SQL_HOST, SqlCfg::CONST_SQL_USER_NAME, SqlCfg::CONST_SQL_PASSWORD) or die("ERROR: Fail to connect to mysql
		database. At line ".__LINE__." in file ".__FILE__."<br>");

	mysql_select_db(SqlCfg::CONST_DB_NAME_HROPTMC, $sqlLink) or die("ERROR: Fail to select the database. At line ".__LINE__."
		in file ".__FILE__."<br>");

	$sqlSetence = "UPDATE  `".SqlCfg::CONST_DB_NAME_HROPTMC."`.`".SqlUtil::DB_NAME_MEETING_AGENDA_ROLES."` SET  `".$keyOfRoleName."` =  null
 		WHERE CONVERT(  `".SqlUtil::DB_NAME_MEETING_AGENDA_ROLES."`.`date` USING utf8 ) =  '".$meetingDate."' LIMIT 1 ;";

	if(mysql_query($sqlSetence, $sqlLink)){
		echo "Y";
	} else {
		die("ERROR: Fail to execute sql query to retrive 
				information of current agenda. At line".__LINE__." in File ".__FILE__."<br>");
	}

	mysql_close($sqlLink);
?>