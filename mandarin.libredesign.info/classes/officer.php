<?php

class Officer{
	private $validDate;
	private $expireDate;
	private $President;
	private $VPE;
	private $VPM;
	private $VPPR;
	private $SAA;
	private $Treasurer;
	private $Secretary;
	
	private $names = array("validDate", "expireDate", "President", "VPE", "VPM", "VPPR", "SAA", "Treasurer", "Secretary");
	
	const PRESIDENT_KEY = 3;
	const VPE_KEY = 4;
	const VPM_KEY = 5;
	const VPPR_KEY = 6;
	const SAA_KEY = 7;
	const TREASURER_KEY = 8;
	const SECRETARY_KEY = 9;
	
	const CONST_TABLE_NAME = "officers";
	const CONST_FIELD_PRESIDENT = "president";
	const CONST_FIELD_VALIDDATE = "ValidDate";
	const CONST_FIELD_EXPIREDATE = "ExpireDate";
	
	public function __construct(){
		$args = func_get_args();
		$argNum = func_num_args();
		
		if($argNum == 9){
			$this->constructOfficer_1($args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6], $args[7], $args[8]);
		} elseif($argNum == 1 && is_array($args[0])){
			$this->constructOfficer_2($args[0]);
		} else {
			die("ERROR: No such constructor, can only receive 1 or 9 arguments for class Officer. At line ".__LINE__." in file ".__FILE__."<br>");
		}
	}
	
	public function constructOfficer_1($vDate, $eDate, $president, $vPE, $vPM, $vPPR, $sAA, $treasurer, $secretary){
		$this->validDate = $vDate;
		$this->expireDate = $eDate;
		$this->President = $president;
		$this->VPE = $vPE;
		$this->VPM = $vPM;
		$this->VPPR = $vPPR;
		$this->SAA = $sAA;
		$this->Treasurer = $treasurer;
		$this->Secretary = $secretary;
	}
	
	public function constructOfficer_2(array $allValues){
		global $officerTableKeys;
		foreach($officerTableKeys as $key => $value){
			if(array_key_exists($value, $allValues)){
				$this->{$this->names[$key]} = $allValues[$value];
			}
		}
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
	
	public static function getCurrentOfficer(){
		return Officer::getOfficer(date("Y-m-d"));
	}
	
	public static function getOfficer($date){
		global $officerTableKeys;
		
		$sqlLink = getMysqlConnection(CONST_MYSQL_HOST, CONST_MYSQL_USER_NAME, CONST_MYSQL_PASSWORD, CONST_DB_NAME_HROPTMC);
		
		$sqlSentence = "select * from ".SqlUtil::DB_NAME_OFFICERS." where ValidDate<='".$date."' and ExpireDate>='".$date."'";
		
		if($queryResult  = mysql_query($sqlSentence, $sqlLink)){
			if(mysql_num_rows($queryResult) == 1){
				$fetchedValues = mysql_fetch_array($queryResult);
				$officerFiltered;
				foreach($officerTableKeys as $key => $value){
					if(array_key_exists($value, $fetchedValues)){
						$officerFiltered[$value] = $fetchedValues[$value];
					}
				}
				
				return new Officer($officerFiltered);
			} elseif(mysql_num_rows($queryResult) == 0){
				die("ERROR: No generation of officers can be found in our database on given date: ".$date.", please check the date given.
					At line".__LINE__." in File ".__FILE__."<br>");
			} else {
				die("ERROR: More than 1 generation of officers is found in database on given date: ".$date.", please contact administrator to correct the database!!!
					At line".__LINE__." in File ".__FILE__."<br>");
			}
		} else {
			die("ERROR: Fail to execute sql query to retrive information of officer on given date: ".$date.". At line".__LINE__." in File ".__FILE__."<br>");
		}
		
		mysql_close($sqlLink);
		
		return false;
	}
	
	public function isOfficer($id){
		for($index = Officer::PRESIDENT_KEY; $index <= count($this->names); $index++){
			if($this->{$this->names[$index - 1]} == $id){
				return $index;
			}
		}
		
		return false;
	}
	
	public function getOfficerDutyName($id){
	    for($index = Officer::PRESIDENT_KEY; $index <= count($this->names); $index++){
	        if($this->{$this->names[$index - 1]} == $id){
	            return $this->names[$index - 1];
	        }
	    }
	    
	    return false;
	}
	
	public static function isInGivenOfficer($date, $id){
		$chosenOfficer = Officer::getOfficer($date);
		return $chosenOfficer->isOfficer($id);
	}
	
	public static function isInCurrentOfficer($id){
		$chosenOfficer = Officer::getCurrentOfficer();
		return $chosenOfficer->isOfficer($id);
	}
	
	public function toString(){
		echo "<br>****************************************************************************<br>";
		foreach($this->names as $key => $value){
			echo $value." => ".((isset($this->$value) && ($this->$value != ""))?$this->$value:"NA")." : ";
		}
		echo "<br>****************************************************************************<br>";
	}
	
	public static function getDefaultAdminLevel($officerKey){
		if($officerKey == Officer::PRESIDENT_KEY || $officerKey == Officer::VPM_KEY){
			return Admin::CONST_ADMIN_LEVEL_SUPER_ADMIN;
		} elseif($officerKey == Officer::VPE_KEY || $officerKey == Officer::VPPR_KEY || $officerKey == Officer::SAA_KEY || $officerKey == Officer::TREASURER_KEY || $officerKey == Officer::SECRETARY_KEY){
			return Admin::CONST_ADMIN_LEVEL_NORMAL_ADMIN;
		}
	}
	
	
	public static function getOfficerId($date, $officerKey){

		$officerId = Member::CONST_NON_MEMBER_ID;
		
		$mysqlUtil = MysqlUtil::getInstance();
		
		// Select President to the coresponding date
		$sqlSentence = "SELECT ".$officerKey." FROM ".Officer::CONST_TABLE_NAME." WHERE ".Officer::CONST_FIELD_VALIDDATE."<='".$date."' and ".Officer::CONST_FIELD_EXPIREDATE.">='".$date."'";
		
		$sqlQuery = $mysqlUtil->queryGetResult($sqlSentence);
		
		if(mysql_num_rows($sqlQuery) > 0){
			$officerId = mysql_fetch_object($sqlQuery)->$officerKey;
		} 
		
		return $officerId;
	}
	
	public static function getOfficerName($date, $officerKey){
		$officerId = Officer::getOfficerId($date, $officerKey);
		$officerName = Member::getMemberFieldByCludId($officerId, Member::CONST_FIELD_MEMBERNAME);
		
		return $officerName;
	}
	
	public function getCurrentOfficerName($officerKey){
	    $officerName = Member::getMemberFieldByCludId($this->{$officerKey}, Member::CONST_FIELD_MEMBERNAME);
	    
	    return $officerName;
	}
	

}

?>