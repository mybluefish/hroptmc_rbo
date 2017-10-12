<?php
class OfficerItem{
	const TABLE_NAME = "OfficerItems";
	
	const VALID_DATE = "ValidDate";
	const EXPIRE_DATE = "ExpireDate";
	const PRESIDENT = "President";
	const VPE = "VPE";
	const VPM = "VPM";
	const VPPR = "VPPR";
	const SAA = "SAA";
	const TREASURER = "Treasurer";
	const SECRETARY = "Secretary";
	
	const nameArray = array(OfficerItem::VALID_DATE, OfficerItem::EXPIRE_DATE, OfficerItem::PRESIDENT, OfficerItem::VPE, OfficerItem::VPM,
							OfficerItem::VPPR, OfficerItem::SAA, OfficerItem::TREASURER, OfficerItem::SECRETARY);
							
	private $ValidDate;
	private $ExpireDate;
	private $President;
	private $VPE;
	private $VPM;
	private $VPPR;
	private $SAA;
	private $Treasurer;
	private $Secretary;
	
	private $presidentName;
	private $vpeName;
	private $vpmName;
	private $vpprName;
	private $saaName;
	private $treasurerName;
	private $secretaryName;
	
	private $isOffierItemExisted;
	private $isInit;
	
	const EMPTY_VALUE = "";
	
	public function __construct($isOfficerItemExisted, $fetchedArray = array()){
		$this->isOffierItemExisted = $isOfficerItemExisted;
		$this->initOfficerItem($fetchedArray);
	}
	
	private function initOfficerItem(array $fetchedArray){
		if(empty($fetchedArray)){
			$this->isInit = false;
			return;
		}
		
		foreach(OfficerItem::nameArray as $valueOfTableKeyName){
			if(array_key_exists($valueOfTableKeyName, $fetchedArray)){
				$this->{$valueOfTableKeyName} = $fetchedArray[$valueOfTableKeyName];
			}
		}
		$this->isInit = true;
	}
	
	public static function getOfficerItemInstanceByDate($date){		
		$sqlLink = SqlUtil::getSqlConnection();
		$sqlSentence = "SELECT * FROM ".OfficerItem::TABLE_NAME." WHERE ".OfficerItem::VALID_DATE."<='".$date."' and ".OfficerItem::EXPIRE_DATE.">='".$date."';";
		$queryObject = mysql_query($sqlSentence, $sqlLink);
		
		if(!$queryObject){
			return new OfficerItem(false);
		}
		
		if(mysql_num_rows($queryObject) == 0){
			return new OfficerItem(false);
		}
		
		$fetchedDatabaseArrays = mysql_fetch_array($queryObject);
		$officerItemObj = new OfficerItem(true, $fetchedDatabaseArrays);
		return $officerItemObj;
	}
	
	public function isOffierItemExisted(){
		return $this->isOffierItemExisted;
	}
	
	public function isOfficerItemInited(){
		return $this->isInit;
	}
	
	public function getOfficerID($officerType){
		if(!$this->isInit()){
			return OfficerItem::EMPTY_VALUE;
		}
		
		return $this->{$officerType};
	}
	
	public function __get($propName){
		if(in_array($propName, OfficerItemItem::nameArray)){
			return $this->$propName;
		}
	}
	
	public function __set($propName, $value){
		if(in_array($propName, OfficerItemItem::nameArray)){
			$this->$propName = $value;
		}
	}
}
?>