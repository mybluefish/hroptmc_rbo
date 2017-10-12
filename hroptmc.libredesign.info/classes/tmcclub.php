<?php
class TmcClub{
	private $tmcclubindex;
	private $tmcclubname;
	private $tmcclubshortname;
	private $valid;
	private $validdate;
	private $expiredate;
	private $tmcclubdescription;
	
	private $names = array("tmcclubindex", "tmcclubname", "tmcclubshortname", "valid", "validdate", "expiredate", "tmcclubdescription");
	
	const CONST_TABLE_NAME = "tmcclubs";
	
	const CONST_NO_OF_FIELDS = 7;
	
	const CONST_FIELD_CLUBINDEX = "tmcclubindex";
	const CONST_FIELD_CLUBNAME = "tmcclubname";
	const CONST_FIELD_CLUBSHOTNAME = "tmcclubshortname";
	const CONST_FIELD_VALIE = "valid";
	const CONST_FIELD_VALIEDATE = "validdate";
	const CONST_FIELD_EXPIREDATE = "expiredate";
	const CONST_FIELD_CLUBDESCRIPTION = "tmcclubdescription";
	
	const CONST_FIELD_VALID_TRUE = 1;
	
	const CONST_TMC_CLUB_DEFAULT = "OTHER";
	
	public function __construct(){
		$args = func_get_args();
		$argNum = func_num_args();
		
		if($argNum == TmcClub::CONST_NO_OF_FIELDS){
			
			$this->constructClub_1($args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6]);
			
		} elseif($argNum == 1 && is_array($args[0])){
			
			$this->constructClub_2($args[0]);
			
		} else {
			
			die("ERROR: No such constructor, can only receive 1, 5 arguments for class Guest. At line ".__LINE__." in file ".__FILE__."<br>");
			
		}
	}
	
	private function constructClub_1($tmcclubindex, $tmcclubname, $tmcclubshortname, $valid, $validdate, $expiredate, $tmcclubdescription){
		$this->tmcclubindex = $tmcclubindex;
		$this->tmcclubname = $tmcclubname;
		$this->tmcclubshortname = $tmcclubshortname;
		$this->valid = $valid;
		$this->validdate = $validdate;
		$this->expiredate = $expiredate;
		$this->tmcclubdescription = $tmcclubdescription;
	}
	
	private function constructClub_2(array $allValues){
		foreach($this->names as $value){
			if(array_key_exists($value, $allValues)){
				$this->{$value} = $allValues[$value];
			}
		}
	}
	
	public static function getClubWithClubIndex($clubIndex){
		$clubObject = false;
		
		$mysqlUtil = MysqlUtil::getInstance();
		
		$sqlSentence = "SELECT * FROM `".TmcClub::CONST_TABLE_NAME."` WHERE `".TmcClub::CONST_FIELD_CLUBINDEX."`='".$clubIndex."';";

		$sqlResult = $mysqlUtil->queryGetResult($sqlSentence);
		
		if($mysqlUtil->getQueryRowNum($sqlResult) != 0){
			$tempArray = $mysqlUtil->getQueryResultArray($sqlResult);
			$clubObject = new TmcClub($tempArray);
		}
		
		return $clubObject;
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
	
	public static function getAllValidClubListWithJson(){
		$mysqlUtil = MysqlUtil::getInstance();
		
		$sqlSentence = "SELECT * FROM `".TmcClub::CONST_TABLE_NAME."` WHERE `".TmcClub::CONST_FIELD_VALIE."`='".TmcClub::CONST_FIELD_VALID_TRUE."';";
		
		$queryRestult = $mysqlUtil->queryGetResult($sqlSentence);
		
		$clubList = array();
		
		while($singleClubQueryArray = $mysqlUtil->getQueryResultArray($queryRestult)){
			$tempClubObj = new TmcClub($singleClubQueryArray);
			$clubList[$tempClubObj->tmcclubindex] = array($tempClubObj->tmcclubname, $tempClubObj->tmcclubshortname);
		}
		
		return json_encode($clubList);
	}
	
}
?>