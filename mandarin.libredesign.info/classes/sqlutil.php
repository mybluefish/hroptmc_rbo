<?php

	class SqlUtil{

		private static $sqlUtil;

		private $sqlLink;

		private $mysqlHost;
		private $userName;
		private $password;
		private $dataBaseName;

		const DB_NAME_MEETING_AGENDA_ROLES = "meetingagendaroles";
		const DB_NAME_MEMBERS = "members";
		const DB_NAME_OFFICERS = "officers";
		const DB_NAME_USERS = "users";
		const DB_NAME_EXCEPTIONDATE = "exceptiondate";
		const DB_NAME_LOGINRECORDS = "loginrecords";

		const DB_NAME_CONTESTMETADATA = "contestmetadata";
		const DB_NAME_CONTESTPUBLISH = "contestpublish";
		const DB_NAME_CONTESTRECORDS = "contestrecords";

		//fieldNames in table DB members
		const MEMBERS_FIELD_NAME_CLUBID = "ClubID";
		const MEMBERS_FIELD_NAME_MEMBERID = "MemberID";
		const MEMBERS_FIELD_NAME_MEMBERNAME = "MemberName";
		const MEMBERS_FIELD_NAME_VALIDSTATUS = "ValidStatus";
		const MEMBERS_FIELD_NAME_LEVELCC = "LevelCC";
		const MEMBERS_FIELD_NAME_LEVELCL = "LevelCL";
		const MEMBERS_FIELD_NAME_CHINESENAME = "ChineseName";
		const MEMBERS_FIELD_NAME_EMAIL = "Email";
		const MEMBERS_FIELD_NAME_PHONENO = "PhoneNo";
		const MEMBERS_FIELD_NAME_QQNUMBER = "QQNumber";
		const MEMBERS_FIELD_NAME_WEIBOID = "WeiboID";
		const MEMBERS_FIELD_NAME_BIRTHDAY = "Birthday";
		const MEMBERS_FIELD_NAME_CURRENTCC = "CurrentCC";
		const MEMBERS_FIELD_NAME_CURRENTCL = "CurrentCL";

		//fieldNames in table DB
		const OFFICERS_FIELD_NAME_VALIDDATE = "ValidDate";
		const OFFICERS_FIELD_NAME_EXPIREDATE = "ExpireDate";
		const OFFICERS_FIELD_NAME_PRESIDENT = "President";
		const OFFICERS_FIELD_NAME_VPE = "VPE";
		const OFFICERS_FIELD_NAME_VPM = "VPM";
		const OFFICERS_FIELD_NAME_VPPR = "VPPR";
		const OFFICERS_FIELD_NAME_SAA = "SAA";
		const OFFICERS_FIELD_NAME_TREASURER = "Treasurer";
		const OFFICERS_FIELD_NAME_SECRETARY = "Secretary";

		//fieldNames in table DB users
		const USERS_FIELD_NAME_CLUBID = "clubid";
		const USERS_FIELD_NAME_USERNAME = "username";
		const USERS_FIELD_NAME_PSWD = "pswd";
		const USERS_FIELD_NAME_ADMINLEVEL = "adminlevel";
		const USERS_FIELD_NAME_ONETIMEURL = "onetimeurl";
		const USERS_FIELD_NAME_SPECIALASSIGNED = "specialassigned";
		const USERS_FIELD_NAME_TOADMINLEVEL = "toadminlevel";
		const USERS_FIELD_NAME_VALIDDATE = "validdate";
		const USERS_FIELD_NAME_EXPIREDATE = "expiredate";

		//fieldNames in table DB exceptiondate
		const USERS_FIELD_NAME_DATE = "Date";
		const USERS_FIELD_NAME_NOTREGULARKEY = "NotRegularKey";
		const USERS_FIELD_NAME_REASON = "Reason";

		//fieldNames in table DB loginrecords
		const LOGINRECORDS_FIELD_NAME_DATETIME = "DateTime";
		const LOGINRECORDS_FIELD_NAME_USERNAME = "UserName";
		const LOGINRECORDS_FIELD_NAME_IPADDRESS = "IpAddress";
		const LOGINRECORDS_FIELD_NAME_LOGINTYPE = "LoginType";

		const CONTESTMETADATA_FIELD_NAME_CONTESTINDEX = "ContestIndex";
		const CONTESTMETADATA_FIELD_NAME_CONTESTTITLE = "ContestTitle";
		const CONTESTMETADATA_FIELD_NAME_LANGUAGE = "Language";

		const CONTESTPUBLISH_FIELD_NAME_STARTDATE = "StartDate";
		const CONTESTPUBLISH_FIELD_NAME_ENDDATE = "EndDate";
		const CONTESTPUBLISH_FIELD_NAME_CONTESTINDEX = "ContestIndex";
		const CONTESTPUBLISH_FIELD_NAME_CONTESTDATE = "ContestDate";

		const CONTESTRECORDS_FIELD_NAME_CLUBID = "ClubID";
		const CONTESTRECORDS_FIELD_NAME_CONTESTINDEX = "ContestIndex";
		const CONTESTRECORDS_FIELD_NAME_CONTESTDATE = "ContestDate";

		private function __construct(){

			$this->mysqlHost = CONST_MYSQL_HOST;
			$this->userName = CONST_MYSQL_USER_NAME;
			$this->password = CONST_MYSQL_PASSWORD;
			$this->dataBaseName = CONST_DB_NAME_HROPTMC;

			$this->sqlLink = mysql_connect($this->mysqlHost, $this->userName, $this->password) or die("ERROR: Fail to connect to databaseAt line ".__LINE__."
				in file ".__FILE__."<br>");

			mysql_select_db($this->dataBaseName) or die("ERROR: Fail to select the database. At line ".__LINE__."
				in file ".__FILE__."<br>");
		}

		public static function getInstance(){
			if(!isset(SqlUtil::$sqlUtil)){
				SqlUtil::$sqlUtil = new SqlUtil();
			}

			return SqlUtil::$sqlUtil;
		}

		public static function getOfficerID($date, $officerName, $sqlLink){

			// Select President to the coresponding date
			$sqlSentence = "select ".$officerName." from ".SqlUtil::DB_NAME_OFFICERS." where ValidDate<='".$date."' and ExpireDate>='".$date."'";

			$sqlQuery = mysql_query($sqlSentence, $sqlLink) or die("ERROR: Fail to execute sql query to retrive
				information of current agenda. At line".__LINE__." in File ".__FILE__."<br>");
			mysql_query("SET NAMES UTF8");
			if(mysql_num_rows($sqlQuery) != 1){
				die("Error No of ".$officerName." can be found in table ".SqlUtil::DB_NAME_OFFICERS." for current date: ".$date.", total ".$mysql_num_rows ."<br>
					At line".__LINE__." in File ".__FILE__."<br>");
			}

			$sqlResult = mysql_fetch_object($sqlQuery);

			return $sqlResult->$officerName;
		}

		public static function getMemberField($clubID, $fieldName, $sqlLink){

			$sqlSentence = "select ".$fieldName." from ".SqlUtil::DB_NAME_MEMBERS." where ClubID='".$clubID."';";
			mysql_query("SET NAMES UTF8");
			$sqlQuery = mysql_query($sqlSentence, $sqlLink) or die("ERROR: Fail to get infomatio of ".$fieldName." for Club ID: ".$clubID.". At line".__LINE__." in File ".__FILE__."<br>");

			$sqlResult = mysql_fetch_object($sqlQuery);

			if(mysql_num_rows($sqlQuery) != 1){
				return false;
			}

			return $sqlResult->$fieldName;
		}

		public static function getPresidentName($date, $sqlLink){
			return SqlUtil::getMemberField(SqlUtil::getOfficerID($date, "President", $sqlLink), "MemberName", $sqlLink);
		}

		public static function getMemberLevel($clubID, $sqlLink){
			$levelCCKey = "LevelCC";
			$levelCLKey = "LevelCL";

			$levelCC = SqlUtil::getMemberField($clubID, $levelCCKey, $sqlLink);
			$levelCL = SqlUtil::getMemberField($clubID, $levelCLKey, $sqlLink);

			$memberLevel = "TM";

			if($levelCC != "TM"){
				$memberLevel = $levelCC;
			}

			if($levelCL != "TM"){
				if($memberLevel != "TM"){
					$memberLevel = $memberLevel.", ".$levelCL;
				} else {
					$memberLevel = $levelCL;
				}
			}

			return $memberLevel;
		}


		public static function getMember($clubID, $sqlLink){
			$sqlSentence = "select * from ".SqlUtil::DB_NAME_MEMBERS." where ClubID='".$clubID."';";
			mysql_query("SET NAMES UTF8");
			$sqlQuery = mysql_query($sqlSentence, $sqlLink);

			if(mysql_num_rows($sqlQuery) == 1){
				return mysql_fetch_object($sqlQuery);
			} else{
				return false;
			}
		}

		public static function findGuestInAgenda($date, $guestIDString, $sqlLink){
			$roleNames = array("tme", "timer", "ahcounter","grammarian", "ttm", "ge", "joke","speaker1", "speaker2",
					"speaker3", "evaluator1","evaluator2", "evaluator3");

			$sqlSentence = "select * from ".SqlUtil::DB_NAME_MEETING_AGENDA_ROLES." where date='".$date."';";
			mysql_query("SET NAMES UTF8");
			$sqlQuery = mysql_query($sqlSentence, $sqlLink);

			$sqlResult = mysql_fetch_object($sqlQuery);

			foreach($roleNames as $key => $value){
				if($sqlResult->$value == "" || $sqlResult->$value == null){
					continue;
				} else {
					$splitRole = explode("/", $sqlResult->$value);
					$splitTargetString = explode("/", $guestIDString);

					if(($splitRole[0]!="m") && (strcasecmp($splitRole[1], $splitTargetString[0])==0 && strcmp($splitRole[2], $splitTargetString[1])==0 && strcmp($splitRole[3], $splitTargetString[2])==0)){
						return $splitRole[0]."~".$splitRole[1]."~".$splitRole[2]."~".$splitRole[3]."--".$splitTargetString[0]."~".$splitTargetString[1]."~".$splitTargetString[2]."~";
						return true;
					} else {
						continue;
					}
				}
			}

			return false;
		}

		public static function getClubIDFromMemberName($memberName, $sqlLink){

			$clubIDField = "ClubID";

			$sqlSentence = "select ".$clubIDField." from ".SqlUtil::DB_NAME_MEMBERS." where lower(MemberName)=lower('".$memberName."');";
			mysql_query("SET NAMES UTF8");
			$sqlQuery = mysql_query($sqlSentence, $sqlLink);

			if(mysql_num_rows($sqlQuery) == 1){
				return mysql_fetch_object($sqlQuery)->$clubIDField;
			} else {
				return false;
			}
		}

		public static function getFetchObjectByGivenField($fieldName, $fieldValue, $tableName, $sqlLink){
			$sqlSentence = "select * from ".$tableName." where ".$fieldName."='".$fieldValue."';";
			mysql_query("SET NAMES UTF8");
			$sqlQuery = mysql_query($sqlSentence, $sqlLink);

			if(mysql_num_rows($sqlQuery) == 0 ){
				return false;
			}

			return mysql_fetch_object($sqlQuery);
		}

		public static function getNumberOfRecordsFound($fieldName, $fieldValue, $tableName, $sqlLink){
			$sqlSentence = "select * from ".$tableName." where ".$fieldName."='".$fieldValue."';";
			mysql_query("SET NAMES UTF8");
			$sqlQuery = mysql_query($sqlSentence, $sqlLink);

			return mysql_num_rows($sqlQuery);
		}

		public static function updateDatabaseByClubID(array $fields, $clubIDFieldName, $clubIDFieldValue, $tableName, $sqlLink){
			$sqlSetence = "UPDATE ".$tableName." SET ";
			$firstCountTag = true;

			if(count($fields) == 0){
				return false;
			}

			foreach($fields as $key => $value){
				if($firstCountTag){
					$firstCountTag = false;
				} else {
					$sqlSetence .=", ";
				}
				$sqlSetence = $sqlSetence.$key."='".$value."'";
			}

			$sqlSetence .= " WHERE ".$clubIDFieldName."='".$clubIDFieldValue."';";
			mysql_query("SET NAMES UTF8");
			if(mysql_query($sqlSetence, $sqlLink)){
				return true;
			} else {
				return false;
			}
		}

		public static function destroySqlUtil(){
			// $this->__destruct();
			// TODO Close mysql connection
		}
	}
?>
