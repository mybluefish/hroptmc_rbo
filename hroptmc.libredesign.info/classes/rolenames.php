<?php

/**
 *  Used to fetch role names from config files or database, and
 *  have the functionality to draw itself into a table on the page
 */
class RoleNames{
	private $nameArray;
	private $names = array("nameArray");

	private static $instance;

	const ROLE_NAME_TABLE_HEADER = "FUNCTIONS / MEETING DATES";

	const TABLE_ID_INDEX = 0;

	const CONST_TABLE_NAME = "rolenames";

	const CONST_FIELD_ROLEKEY = "RoleKey";
	const CONST_FIELD_ROLENAME = "RoleName";
	const CONST_FIELD_ALLOWDUPLICATED = "AllowDuplicated";
	const CONST_FIELD_SHOWORDER = "ShowOrder";
	const CONST_FIELD_ISSHOWN = "IsShown";

	const CONST_VALUE_DUPLICATED_ALLOWED = 1;
	const CONST_VALUE_DUPLICATED_NOT_ALLOWED = 0;

	const CONST_VALUE_THEME = "theme";

	const CONST_VALUE_PRESIDENT = "president";
	const CONST_VALUE_SAA = "saa";
	const CONST_VALUE_JOKE = "joke";
	const CONST_VALUE_MOTIVATOR = "motivator";
	const CONST_VALUE_MINI_WORKSHOP = "workshop";

	const CONST_VALUE_TME = "tme";
	const CONST_VALUE_TIMER = "timer";
	const CONST_VALUE_AHCOUNTER = "ahcounter";
	const CONST_VALUE_GRAMMARIAN = "grammarian";
	const CONST_VALUE_TTM = "ttm";
	const CONST_VALUE_GE = "ge";

	const CONST_VALUE_SPEAKER_PREFIX = "speaker";
	const CONST_VALUE_IE_PREFIX = "evaluator";

// 	const CONST_VALUE_

	private static $ROLE_LIST_NON_CC_CL = array(RoleNames::CONST_VALUE_PRESIDENT,
												RoleNames::CONST_VALUE_SAA,
												RoleNames::CONST_VALUE_JOKE,
												RoleNames::CONST_VALUE_MOTIVATOR,
												RoleNames::CONST_VALUE_MINI_WORKSHOP);

	private static $CL_ROLE_LIST_WITHOUT_EVALUATOR = array(RoleNames::CONST_VALUE_TME,
														   RoleNames::CONST_VALUE_TIMER,
														   RoleNames::CONST_VALUE_AHCOUNTER,
														   RoleNames::CONST_VALUE_GRAMMARIAN,
														   RoleNames::CONST_VALUE_TTM,
														   RoleNames::CONST_VALUE_GE);

	const CONST_VALUE_NO_OF_SPEAKERS_EVALUATORS = 4;


	const CONST_VALUE_CC = "cc";
	const CONST_VALUE_CL = "cl";
	const CONST_VALUE_NO_CC_CL = "nocccl";

	const CONST_NUM_OLD_NO_CC_MEMBER = 3;
	const CONST_NUM_OLD_NO_CC_NONE_MEMBER = 4;
	const CONST_NUM_OLD_CC_MEMBER = 5;
	const CONST_NUM_OLD_CC_NON_MEMBER = 6;

	/**
	 * All role names are stored in datebase rolenames incase there are changes of roles
	 */
	private function __construct(){
		$mysqlUtil = MysqlUtil::getInstance();

		$queryRoleKeysAndNamesSen = "SELECT ".RoleNames::CONST_FIELD_ROLEKEY.", ".RoleNames::CONST_FIELD_ROLENAME." FROM ".RoleNames::CONST_TABLE_NAME." WHERE ".RoleNames::CONST_FIELD_ISSHOWN."=1 ORDER BY ".RoleNames::CONST_FIELD_SHOWORDER." ASC;";
		$queryRoleKeysAndNamesResult = $mysqlUtil->queryGetResult($queryRoleKeysAndNamesSen);

		while ($singleRole = mysql_fetch_object($queryRoleKeysAndNamesResult)){
			$this->nameArray[$singleRole->{RoleNames::CONST_FIELD_ROLEKEY}] = $singleRole->{RoleNames::CONST_FIELD_ROLENAME};
		}
	}

	public static function getInstance(){
		if(!isset(RoleNames::$instance)){
			RoleNames::$instance = new RoleNames();
		}
		return RoleNames::$instance;
	}

	/**
	 * Draw roleNames to a single table
	 * @param unknown_type $tableClass
	 * @param unknown_type $trClass
	 * @param unknown_type $tdClass
	 * @param unknown_type $tableId
	 * @param unknown_type $trId
	 * @param unknown_type $tdId
	 */
	public function drawToTable($tableClass, $trClass, $tdClass, $tableId, $trId, $tdId){
		echo "<table".(isset($tableClass)?" class='".$tableClass."'":"").
			(isset($tableId)?" id='".$tableId."'":"").">";

		echo "<tr".(isset($trClass)?" class='".$trClass."'":"").
			(isset($trId)?" id='".$trId."'":"")."><td".
			(isset($tdClass)?" class='".$tdClass."'":"").
			(isset($tdId)?" id='".$tdId."'":"").">".RoleNames::ROLE_NAME_TABLE_HEADER."</td></tr>";

		foreach ($this->nameArray as $key => $value) {
			echo "<tr".(isset($trClass)?" class='".$trClass."'":"").
			(isset($trId)?" id='".$tdId."'":"")."><td".
			(isset($tdClass)?" class='".$tdClass."'":"").">".$value."</td></tr>";
		}

		echo "</table>";
	}

	public function getNumberOfRoles(){
		return count($this->nameArray);
	}

	public function getOnlyHeader($roleNameHeaderClass, $noOfAgendasInOneScreen){
		$roleNameHeaderID = "roleHeader";
		return "<td id='".$roleNameHeaderID.RoleNames::TABLE_ID_INDEX."' ".(isset($roleNameHeaderClass)?"class='".
					$roleNameHeaderClass."'":"")." onMouseOver=\"changeColorMouseOverOrOut('".$roleNameHeaderID."', ".
					$noOfAgendasInOneScreen.", true)\" onMouseOut=\"changeColorMouseOverOrOut('".$roleNameHeaderID."', ".
					$noOfAgendasInOneScreen.", false)\">".RoleNames::ROLE_NAME_TABLE_HEADER."</td>";
	}

	public function getSingleRoleName($roleNameClass, $roleIndex, $noOfAgendasInOneScreen){
		$keys = array_keys($this->nameArray);
		return "<td id='".$keys[$roleIndex].RoleNames::TABLE_ID_INDEX."' ".(isset($roleNameClass)?"class='".
				$roleNameClass."'":"")." onMouseOver=\"changeColorMouseOverOrOut('".$keys[$roleIndex]."', ".
				$noOfAgendasInOneScreen.", true)\" onMouseOut=\"changeColorMouseOverOrOut('".$keys[$roleIndex].
				"', ".$noOfAgendasInOneScreen.", false)\">".strtoupper($this->nameArray[$keys[$roleIndex]])."</td>";
	}

	public function __get($propName){
		if(in_array($propName, $this->names)){
			return $this->$propName;
		}
	}

	public function getRoleNameByRoleKey($roleKey){
		return $this->nameArray[$roleKey];
	}


	public static function isTheme($roleNameKey){
		return ($roleNameKey == RoleNames::CONST_VALUE_THEME);
	}

	public static function isPresident($roleNameKey){
		return ($roleNameKey == RoleNames::CONST_VALUE_PRESIDENT);
	}

	public static function isTme($roleNameKey){
		return ($roleNameKey == RoleNames::CONST_VALUE_TME);
	}

	/**
	 * To see whether $roleNameKey is duplication allowed
	 * @param unknown $roleNameKey
	 * @return boolean
	 */
	public static function isRoleKeysAllowDuplicated($roleNameKey){
		$isRoleNameKeyFound = false;

		$mysqlUtil = MysqlUtil::getInstance();

		$roleKeySqlSentence = "select `".RoleNames::CONST_FIELD_ROLEKEY."` from `".RoleNames::CONST_TABLE_NAME."` where `".RoleNames::CONST_FIELD_ALLOWDUPLICATED."`=".RoleNames::CONST_VALUE_DUPLICATED_ALLOWED.";";

		$query = $mysqlUtil->queryGetResult($roleKeySqlSentence);

		while($queryObject = $mysqlUtil->getQueryResultObject($query)){
			if($roleNameKey == $queryObject->{RoleNames::CONST_FIELD_ROLEKEY}){
				$isRoleNameKeyFound = true;
				break;
			}
		}

		return $isRoleNameKeyFound;
	}


	/**
	 * To see whether this club id is found registered as an role that can not be duplicated booked.
	 * @param unknown $date
	 * @param unknown $clubId
	 * @return boolean
	 */
	public static function isDuplicatedRoleSubmitted($date, $clubId){

		$aMember = Member::getMemberInstanceFromDB($clubId);

		$mysqlUtil = MysqlUtil::getInstance();

		$roleKeySqlSentence = "SELECT `".RoleNames::CONST_FIELD_ROLEKEY."` FROM `".RoleNames::CONST_TABLE_NAME."` WHERE `".RoleNames::CONST_FIELD_ALLOWDUPLICATED."`=".RoleNames::CONST_VALUE_DUPLICATED_NOT_ALLOWED.";";

		$query = $mysqlUtil->queryGetResult($roleKeySqlSentence);

		$roleNames = array();

		while($roleNamesEach = $mysqlUtil->getQueryResultObject($query)){
			array_push($roleNames, $roleNamesEach->{RoleNames::CONST_FIELD_ROLEKEY});
		}

		$sqlSentence = "SELECT * FROM ".RegularMeeting::CONST_TABLE_NAME." WHERE `".RegularMeeting::CONST_FIELD_DATE."`='".$date."';";

		$sqlQuery = $mysqlUtil->queryGetResult($sqlSentence);

		$sqlResult = $mysqlUtil->getQueryResultObject($sqlQuery);

		foreach($roleNames as $key => $value){
			if($sqlResult->$value == "" || $sqlResult->$value == null){
				continue;
			} else {
				$splitRole = explode("/", $sqlResult->$value);
				if($splitRole[0] == FieldContentInAgenda::NONE_MEMBER_TAG){
					continue;
				}
				if($aMember->{Member::CONST_FIELD_CLUBID} != $splitRole[1]){
					continue;
				} else {
					return true;
				}
			}
		}

		return false;
	}


	// To check on role names
	public static function isRoleTypeCC($roleType){
		if(strpos($roleType, RoleNames::CONST_VALUE_SPEAKER_PREFIX) !== false){
			return true;
		} else {
			return false;
		}
	}

	public static function isRoleTypeEvaluator($roleType){
		if(strpos($roleType, RoleNames::CONST_VALUE_IE_PREFIX) !== false){
			return true;
		} else {
			return false;
		}
	}

	public static function isRoleTypeCLWithoutEvaluator($roleType){
		if(in_array($roleType, RoleNames::$CL_ROLE_LIST_WITHOUT_EVALUATOR)){
			return true;
		} else {
			return false;
		}
	}

	public static function isRoleTypeNoCCAndNoCL($roleType){
		if(in_array($roleType, RoleNames::$ROLE_LIST_NON_CC_CL)){
			return true;
		} else {
			return false;
		}
	}

	public static function isRoleTypeCL($roleType){
		if(RoleNames::isRoleTypeEvaluator($roleType) || RoleNames::isRoleTypeCLWithoutEvaluator($roleType)){
			return true;
		} else {
			return false;
		}
	}

	public static function isRoleTypeCCOrCL($roleType){
		if(RoleNames::isRoleTypeCC($roleType) || RoleNames::isRoleTypeEvaluator($roleType) || RoleNames::isRoleTypeCLWithoutEvaluator($roleType)){
			return true;
		} else {
			return false;
		}
	}

	public static function isRoleTypeNoSpeakerInOldFormat($roleType){
		if(RoleNames::isRoleTypeCL($roleType) || RoleNames::isRoleTypeNoCCAndNoCL($roleType)){
			return true;
		} else {
			return false;
		}
	}
}
?>
