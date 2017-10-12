<?php

class Member{
	private $ClubID;
	private $MemberID;
	private $MemberName;
	private $ValidStatus;
	private $LevelCC;
	private $LevelCL;
	private $ChineseName;
	private $Email;
	private $PhoneNo;
	private $QQNumber;
	private $WeiboID;
	private $Birthday;
	private $CurrentCC;
	private $CurrentCL;

	private $names = array("ClubID", "MemberID", "MemberName", "ValidStatus", "LevelCC", "LevelCL", "ChineseName",
			"Email", "PhoneNo", "QQNumber", "WeiboID", "Birthday", "CurrentCC", "CurrentCL");


	public static $CC = array("TM", "CC", "ACB", "ACS", "ACG", "DTM");
	public static $CL = array("TM", "CL", "ALB", "ALS", "DTM");

	const DEFAULT_LEVEL = "TM";
	const MAX_PROJECT = 10;
	const MAX_CC = 5;
	const MAX_CL = 4;

	const CONST_TABLE_NAME = "members";

	const VALID_STATUS_CONST = 1;
	const VALID_STATUS_CONST_KEY = "ValidStatus";
	const VALID_STATUS_CONST_YES = "Active";
	const VALID_STATUS_CONST_NO = "InActive";
	const BIRTHDAY_CONST_TEXT = "Birthday";
	const PROJECT_STATUS_CONST = "CurrentC";

	const CONST_FIELD_CLUBID = "ClubID";
	const CONST_FIELD_MEMBERNAME = "MemberName";

	const NA_FIELD_CONST = "-";

	const CONST_NON_MEMBER_ID  = 0;

	/**
	 * This way to implement the overload of constructor
	 */
	public function __construct(){
		$args = func_get_args();
		$argNum = func_num_args();
		if($argNum == 14){
			$this->constructMember_1($args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6],
					$args[7], $args[8], $args[9], $args[10], $args[11], $args[12], $args[13]);
		} elseif($argNum == 8){
			$this->constructMember_2($args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6], $args[7]);
		} elseif($argNum == 1 && is_array($args[0])){
			$this->constructMember_3($args[0]);
		} else {
			die("ERROR: No such constructor, can only receive 1, 7 or 13 arguments for class Member. At line ".__LINE__." in file ".__FILE__."<br>");
		}
	}

	/**
	 * This constructor constructs the member object with all of the attributes needed for the member
	 * @param unknown_type $mID
	 * @param unknown_type $mName
	 * @param unknown_type $vStatus
	 * @param unknown_type $lCC
	 * @param unknown_type $lCL
	 * @param unknown_type $cName
	 * @param unknown_type $mail
	 * @param unknown_type $pNo
	 * @param unknown_type $qNumber
	 * @param unknown_type $wID
	 * @param unknown_type $bDay
	 * @param unknown_type $cCC
	 * @param unknown_type $cCL
	 */
	private function constructMember_1($cID, $mID, $mName, $vStatus, $lCC, $lCL, $cName, $mail, $pNo, $qNumber, $wID, $bDay, $cCC, $cCL){
		$this->ClubID = $cID;
		$this->MemberID = $mID;
		$this->MemberName = $mName;
		$this->ValidStatus = $vStatus;
		$this->LevelCC = $lCC;
		$this->LevelCL = $lCL;
		$this->ChineseName = $cName;
		$this->Email = $mail;
		$this->PhoneNo = $pNo;
		$this->QQNumber = $qNumber;
		$this->WeiboID = $wID;
		$this->Birthday = $bDay;
		$this->CurrentCC = $cCC;
		$this->CurrentCL = $cCL;
	}

	/**
	 * This constructor get six mandatory parameters to initilize a member object
	 * @param unknown_type $mID
	 * @param unknown_type $mName
	 * @param unknown_type $vStatus
	 * @param unknown_type $lCC
	 * @param unknown_type $lCL
	 * @param unknown_type $cCC
	 * @param unknown_type $cCL
	 */
	private function constructMember_2($cID, $mID, $mName, $vStatus, $lCC, $lCL, $cCC, $cCL){
		$this->ClubID = $cID;
		$this->MemberID = $mID;
		$this->MemberName = $mName;
		$this->ValidStatus = $vStatus;
		$this->LevelCC = $lCC;
		$this->LevelCL = $lCL;
		$this->CurrentCC = $cCC;
		$this->CurrentCL = $cCL;
	}

	/**
	 * @param array $allValues  All values for the member information
	 */
	private function constructMember_3(array $allValues){
		foreach($this->names as $value){
			if(array_key_exists($value, $allValues)){
				$this->{$value} = $allValues[$value];
			}
		}
	}


	public static function getMemberInstanceFromDB($memberId){
		$memberObject = false;

		$mysqlUtil = MysqlUtil::getInstance();

		$sqlSentence = "SELECT * FROM ".Member::CONST_TABLE_NAME." WHERE `".Member::CONST_FIELD_CLUBID."`=".$memberId.";";
		mysql_query("SET NAMES UTF8");
		$queryResult = $mysqlUtil->queryGetResult($sqlSentence);

		if($mysqlUtil->getQueryRowNum($queryResult) != 0){
			if($resultArray = $mysqlUtil->getQueryResultArray($queryResult)){
				$memberObject =  new Member($resultArray);
			}
		}

		return $memberObject;
	}

	/**
	 * Getter, to get the parametr of a Member Object
	 * @param unknown_type $propName
	 */
	public function __get($propName){
		if(in_array($propName, $this->names)){
			return $this->$propName;
		}
	}

	/**
	 * Setter, to set the value of parameter
	 * @param unknown_type $propName
	 * @param unknown_type $value
	 */
	public function __set($propName, $value){
		if(in_array($propName, $this->names)){
			$this->$propName = $value;
		}
	}

	/**
	 * return the CC level of next speech
	 * @return multitype:string |unknown_type
	 */
	public function getNextLevelCC(){
		return Member::$CC[$this->getNextLevelCCByIndex()];
	}

	public function getNextLevelCCByIndex(){
		$nextCC = array_search($this->LevelCC, Member::$CC);

		if($nextCC != Member::MAX_CC){
			$nextCC++;
		}

		if($this->getNextProjectByNumber($this->CurrentCC) > Member::MAX_PROJECT){
			if($nextCC < Member::MAX_CC){
				$nextCC++;
			}
		}

		return $nextCC;
	}

	/**
	 * return the CL level of next speech
	 * @return multitype:string |unknown_type
	 */
	public function getNextLevelCL(){
		return Member::$CL[$this->getNextLevelCLByIndex()];
	}

	public function getNextLevelCLByIndex(){
		$nextCL = array_search($this->LevelCL, Member::$CL);

		if($nextCL != Member::MAX_CL){
			$nextCL++;
		}

		if($this->getNextProjectByNumber($this->CurrentCL) > Member::MAX_PROJECT){
			if($nextCL < Member::MAX_CL){
				$nextCL++;
			}
		}

		return $nextCL;
	}

	/**
	 * return next CC project NO.
	 * @return number
	 */
	public function getNextProjectCC(){
		$nextProjNo = $this->getNextProjectByNumber($this->CurrentCC);
		if(($nextProjNo % Member::MAX_PROJECT != 0) && (!(($this->CurrentCC == Member::MAX_PROJECT) && ($this->LevelCC == Member::$CC[Member::MAX_CC])))){
			return $nextProjNo % Member::MAX_PROJECT;
		} else {
			return Member::MAX_PROJECT;
		}
	}

	/**
	 * return next CL project NO.
	 * @return number
	 */
	public function getNextProjectCL(){
		$nextProjNo = $this->getNextProjectByNumber($this->CurrentCL);
		if(($nextProjNo % Member::MAX_PROJECT != 0) && (!(($this->CurrentCL == Member::MAX_PROJECT) && ($this->LevelCL == Member::$CL[Member::MAX_CL])))){
			return $nextProjNo % Member::MAX_PROJECT;
		} else {
			return Member::MAX_PROJECT;
		}
	}

	/**
	 * Parse the current project NO. and return next project NO.
	 * @param unknown_type $projTarget
	 * @return number
	 */
	private function getNextProjectByNumber($projTarget){
		if(isset($projTarget)){
			return ++$projTarget;
		} else {
			return 1;
		}
	}

	public function isValidMember(){
		return ($this->ValidStatus == Member::VALID_STATUS_CONST);
	}

	public static function isGivenIdValidMember($clubId, $sqlLink){
		if($memberObject = SqlUtil::getFetchObjectByGivenField(SqlUtil::MEMBERS_FIELD_NAME_CLUBID, $clubId, SqlUtil::DB_NAME_MEMBERS, $sqlLink)){
			return $memberObject->{SqlUtil::MEMBERS_FIELD_NAME_VALIDSTATUS} == 1;
		}

		return false;
	}

	public static function isMemberExist($clubId, $sqlLink){
		if(SqlUtil::getNumberOfRecordsFound(SqlUtil::MEMBERS_FIELD_NAME_CLUBID, $clubId, SqlUtil::DB_NAME_MEMBERS, $sqlLink) == 1){
			return true;
		} else {
			return false;
		}
	}

	public static function GetAMember($idType, $id){
		global $memberTableKeys;

		$idTypes = array("ClubID", "MemberID");
		if(($idType < 0) && ($idType >= count($idTypes))){
			die("Wrong ID Type provided!! At line".__LINE__." in File ".__FILE__."<br>");
		}
		mysql_query("SET NAMES UTF8");
		$sqlLink = mysql_connect(CONST_MYSQL_HOST, CONST_MYSQL_USER_NAME, CONST_MYSQL_PASSWORD) or die("ERROR: Fail to connect to mysql
				database. At line ".__LINE__." in file ".__FILE__."<br>");

		mysql_select_db(CONST_DB_NAME_HROPTMC, $sqlLink) or die("ERROR: Fail to select the database. At line ".__LINE__."
				in file ".__FILE__."<br>");

		mysql_query("set names utf8");

		$sqlQuerySentence = "select * from ".Member::CONST_TABLE_NAME." where ".$idTypes[$idType]."='".$id."'";

		$queryResult  = mysql_query($sqlQuerySentence, $sqlLink) or die("ERROR: Fail to execute sql query to retrive
				information of all members. At line".__LINE__." in File ".__FILE__."<br>");

		if(mysql_num_rows($queryResult) == 1){
			$fetchedValues = mysql_fetch_array($queryResult);
			$memberRecordFiltered;
			foreach($memberTableKeys as $key => $value){
				if(array_key_exists($value, $fetchedValues)){
					$memberRecordFiltered[$value] = $fetchedValues[$value];
				}
			}

			return new Member($memberRecordFiltered);
		} elseif(mysql_num_rows($queryResult) == 0) {
			die("ERROR: No such member is found in our database, please check the MemberID.
					At line".__LINE__." in File ".__FILE__."<br>");
		} else {
			die("ERROR: More than 1 MemberID is found in database, please contact administrator to correct the database!!!
					At line".__LINE__." in File ".__FILE__."<br>");
		}

		mysql_close($sqlLink);

		return false;
	}

	public function toString(){
		echo "<br>****************************************************************************<br>";
		foreach($this->names as $key => $value){
			echo $value." => ".((isset($this->$value) && ($this->$value != ""))?$this->$value:"NA")." : ";
		}
		echo "<br>****************************************************************************<br>";
	}

	public function getSingleMemberLine($trId, $hiddenId, $editAble, $happyBirthdayId, $showAllMembersTag){
		$memberLineStrBuffer = "";
		$hiddenValue = "";

		foreach($this->names as $key => $value){
			if($key == (count($this->names) - 1)){
				$hiddenValue .= $this->formatField($this->$value);
			} else {
				$hiddenValue = $hiddenValue.$this->formatField($this->$value)."::";
			}

			$memberLineStrBuffer .= "<td>";
			if($value == Member::VALID_STATUS_CONST_KEY){
				if($this->isValidMember()){
					$memberLineStrBuffer .= $this->formatField(Member::VALID_STATUS_CONST_YES);
				} else {
					$memberLineStrBuffer .= $this->formatField(Member::VALID_STATUS_CONST_NO);
				}
			} elseif(strpos($value, Member::PROJECT_STATUS_CONST) !== false){
				if($this->formatField($this->$value) != Member::NA_FIELD_CONST){
					$memberLineStrBuffer = $memberLineStrBuffer."P".$this->formatField($this->$value);
				} else {
					$memberLineStrBuffer .= $this->formatField($this->$value);
				}
			} elseif($value == Member::BIRTHDAY_CONST_TEXT){
				if($this->formatField($this->$value) != Member::NA_FIELD_CONST){
					$memberLineStrBuffer = $memberLineStrBuffer.date("M", strtotime($this->$value))." ".date("j", strtotime($this->$value));
				} else {
					$memberLineStrBuffer .= $this->formatField($this->$value);
				}
			} else {
				$memberLineStrBuffer .= $this->formatField($this->$value);
			}
			$memberLineStrBuffer .= "</td>";
		}
		if($editAble != 0){
			$memberLineStrBuffer = $memberLineStrBuffer."<td>(<a href='javascript:void(0)' onClick=\"editMemberLine('".$trId."', '".$hiddenId."', '".$happyBirthdayId."', ".($showAllMembersTag ? "true" : "false").")\">E</a>)</td>";
			$memberLineStrBuffer = $memberLineStrBuffer."<input type='hidden' id='".$hiddenId."' value='".$editAble."::".$hiddenValue."' />";
		} else {
			$memberLineStrBuffer = $memberLineStrBuffer."<td>&nbsp;</td>";
		}

		return $memberLineStrBuffer;
	}

	private function formatField($strToFormat){
		if(!$this->isFieldEmpty($strToFormat)){
			return $strToFormat;
		} else {
			return Member::NA_FIELD_CONST;
		}
	}

	private function isFieldEmpty($field2Check){
		return !(isset($field2Check) && ($field2Check != "") && ($field2Check != null));
	}



	/**
	 * Try to get any field with $fieldName for $cludId
	 * @param int $cludId
	 * @param string $fieldName
	 * @return string
	 */
	public static function getMemberFieldByCludId($cludId, $fieldName){
		$fieldValue = Member::NA_FIELD_CONST;

		$querySentence = "SELECT `".$fieldName."` FROM `".Member::CONST_TABLE_NAME."` WHERE `".Member::CONST_FIELD_CLUBID."`=".$cludId.";";

		$mysqlUtil = MysqlUtil::getInstance();

		$mysqlQuery = $mysqlUtil->queryGetResult($querySentence);

		if((mysql_num_rows($mysqlQuery) != 0) && ($cludId != Member::CONST_NON_MEMBER_ID)) {
			$fieldValue = mysql_fetch_object($mysqlQuery)->{$fieldName};
		}

		return $fieldValue;
	}

	public function getMemberLevel(){
		$memberLevel = Member::DEFAULT_LEVEL;

		if(isset($this->LevelCC) && ($this->LevelCC != Member::DEFAULT_LEVEL)){
			$memberLevel = $this->LevelCC;
		}

		if(isset($this->LevelCL) && ($this->LevelCL != Member::DEFAULT_LEVEL)){
			if($memberLevel != Member::DEFAULT_LEVEL){
				$memberLevel = $memberLevel.", ".$this->LevelCL;
			} else {
				$memberLevel = $this->LevelCL;
			}
		}

		return $memberLevel;
	}

	public function getFormalFormatOfMemberName(){
		$memberLevel = Member::DEFAULT_LEVEL;

		if($this->LevelCC != Member::DEFAULT_LEVEL){
			$memberLevel = $this->LevelCC;
		}

		if($this->LevelCL != Member::DEFAULT_LEVEL){
			if($memberLevel != Member::DEFAULT_LEVEL){
				$memberLevel = $memberLevel.", ".$this->LevelCL;
			} else {
				$memberLevel = $this->LevelCL;
			}
		}

		return $this->MemberName." (".$memberLevel.")";
	}

	public static function getClubIDWithMemberName($memberName){
		$mysqlUtil = MysqlUtil::getInstance();

		$sqlSentence = "SELECT ".Member::CONST_FIELD_CLUBID." FROM ".Member::CONST_TABLE_NAME." WHERE LOWER(".Member::CONST_FIELD_MEMBERNAME.")=LOWER('".$memberName."');";

		$mysqlQuery = $mysqlUtil->queryGetResult($sqlSentence);

		if($mysqlUtil->getQueryRowNum($mysqlQuery) == 1){
			return $mysqlUtil->getQueryResultObject($mysqlQuery)->{Member::CONST_FIELD_CLUBID};
		} else {
			return false;
		}
	}
}


/**
 * Test
 */
// Member::GetAMember(1)->toString();
?>
