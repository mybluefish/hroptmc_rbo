<?php
class Guest{
	private $guestid;
	private $guestname;
	private $tmcclubindex;
	private $phonenumber;
	private $email;
	
	private $isGuestInNewFormat;
	
	private $firstregdate;
	private $lastregdate;
	private $lastactdate;
	
	private $names = array("guestid", "guestname", "tmcclubindex", "phonenumber", "email", "firstregdate", "lastregdate", "lastactdate");
	
	const CONST_TABLE_NAME = "guests";
	
	const CONST_FIELD_GUESTID = "guestid";
	const CONST_FIELD_GUESTNAME = "guestname";
	const CONST_FIELD_TMCCLUBINDEX = "tmcclubindex";
	const CONST_FIELD_PHONENUMBER = "phonenumber";
	const CONST_FIELD_EMAIL = "email";
	const CONST_FIELD_FIRSTREGDATE = "firstregdate";
	const CONST_FIELD_LASTREGDATE = "lastregdate";
	const CONST_FIELD_LASTACTDATE = "lastactdate";
	
	
	
	/**
	 * This way to implement the overload of constructor
	 */
	public function __construct(){
		$args = func_get_args();
		$argNum = func_num_args();
		if($argNum == 5){
			$this->constructGuest_1($args[0], $args[1], $args[2], $args[3], $args[4]);
		} elseif($argNum == 1 && is_array($args[0])){
			$this->constructGuest_2($args[0]);
		} else {
			die("ERROR: No such constructor, can only receive 1, 5 arguments for class Guest. At line ".__LINE__." in file ".__FILE__."<br>");
		}
		
		$this->isGuestInNewFormat = true;
	}
	
	public function constructGuest_1($guestid, $guestname, $tmcclubindex, $phonenumber, $email){
		$this->guestid = $guestid;
		$this->guestname = $guestname;
		$this->tmcclubindex = $tmcclubindex;
		$this->phonenumber = $phoneNumber;
		$this->email = $email;
	}
	
	private function constructGuest_2(array $allValues){
		foreach($this->names as $value){
			if(array_key_exists($value, $allValues)){
				$this->{$value} = $allValues[$value];
			}
		}
	}
	
// 	public static function getGuestIdWithPhoneNumber($phoneNumber){
		
	
// 	}
	
// 	public static function getGuestIdWithEmail($email){
		
// 	}

// 	public static function getPhoneNumberWithGuestId($guestId){
		
// 	}
	
// 	public static function getEmailWithGuestId($guestId){
		
// 	}
	
	public static function getGuestWithIdenticalInfo($guestName, $clubIndex, $phoneNumber, $email, $meetingDateString){
		
		$guestObject = false;
		
		$mysqlUtil = MysqlUtil::getInstance();
		
		$sqlSetenceSelect = "SELECT * FROM ".Guest::CONST_TABLE_NAME." WHERE `".Guest::CONST_FIELD_PHONENUMBER."`='".$phoneNumber."';";
		
		$queryResult = $mysqlUtil->queryGetResult($sqlSetenceSelect);
		
// 		if($mysqlUtil->getQueryRowNum($queryResult) == 0){
// 			$sqlSetenceInsert = "INSERT INTO `".Guest::CONST_TABLE_NAME."` (`".Guest::CONST_FIELD_GUESTNAME."`, `".Guest::CONST_FIELD_TMCCLUBINDEX."`,`".Guest::CONST_FIELD_PHONENUMBER."`, `".
// 					Guest::CONST_FIELD_EMAIL."`, `".Guest::CONST_FIELD_FIRSTREGDATE."`) VALUES (`".
// 					$guestName."`, `".$clubName."`, `".$phoneNumber."`, `".$email."`, `".$meetingDateString."`);";
			
// 			$queryResult = $mysqlUtil->queryGetResult($sqlSetenceInsert);
			
// 			if($mysqlUtil->getQueryRowNum($queryResult)){
// 				$queryResult = $mysqlUtil->queryGetResult($sqlSetenceSelect);
// 			}
			
// 			if($mysqlUtil->getQueryRowNum($queryResult) == 0){
// 				return $guestObject;
// 			}
// 		}
		
// 		$tempArray = $mysqlUtil->getQueryResultArray($queryResult);
// 		$guestObject = new Guest($tempArray);
		
		
		if($mysqlUtil->getQueryRowNum($queryResult) != 0){
			$tempArray = $mysqlUtil->getQueryResultArray($queryResult);
			$guestObject = new Guest($tempArray);
		} else {
			$sqlSetenceInsert = "INSERT INTO `".Guest::CONST_TABLE_NAME."`(`".Guest::CONST_FIELD_GUESTNAME."`, `".Guest::CONST_FIELD_TMCCLUBINDEX."`,`".Guest::CONST_FIELD_PHONENUMBER."`, `".
									Guest::CONST_FIELD_EMAIL."`, `".Guest::CONST_FIELD_FIRSTREGDATE."`) VALUES ('".
									$guestName."', '".$clubIndex."', '".$phoneNumber."', '".$email."', '".date("Y-n-j")."');";
			
			$queryResult = $mysqlUtil->queryGetResult($sqlSetenceInsert);
			
			
			if($queryResult){
				$queryResult = $mysqlUtil->queryGetResult($sqlSetenceSelect);
				if($mysqlUtil->getQueryRowNum($queryResult) != 0){
					$tempArray = $mysqlUtil->getQueryResultArray($queryResult);
					$guestObject = new Guest($tempArray);
				}
			}
		}
		
// 		if($guestObject){
// 			$guestObject->updateLastActivityDateUpToDate();
// 		}
		
		return $guestObject;
	}
	
	
	
	public static function getGuestInstanceFromDBWithPhoneNumer($phoneNumber){
		return Guest::getGuestInstanceFromDB(Guest::CONST_FIELD_PHONENUMBER, $phoneNumber);
	}
	
	public static function getGuestInstanceFromDBWithEmail($email){
		return Guest::getGuestInstanceFromDB(Guest::CONST_FIELD_EMAIL, $email);
	}
	
	public static function getGuestInstanceFromDBWithGuestId($guestId){
		return Guest::getGuestInstanceFromDB(Guest::CONST_FIELD_GUESTID, $guestId);
	}
	
	private static function getGuestInstanceFromDB($key, $value){
		$guestObject = false;
		
		$mysqlUtil = MysqlUtil::getInstance();
		
		$sqlSentence = "SELECT * FROM ".Guest::CONST_TABLE_NAME." WHERE `".$key."`=".$value.";";
		
		$queryResult = $mysqlUtil->queryGetResult($sqlSentence);
		
		if($mysqlUtil->getQueryRowNum($queryResult) != 0){
			if($resultArray = $mysqlUtil->getQueryResultArray($queryResult)){
				$guestObject =  new Guest($resultArray);
			}
		}
		
		return $guestObject;
	}
	
	public function isSameId($guestId){
		return ($this->guestid == $guestId);
	}
	
	public function setGuestId($guestId){
		$this->guestid = $guestId;
	}
	
	public function getGuestId(){
		return $this->guestid;
	}
	
	public function getGuestName(){
		return $this->guestname;
	}
	
	public function getTmcClubIndex(){
		return $this->tmcclubindex;
	}
	
	public function getTmcClubName(){
		$tempClubObject = TmcClub::getClubWithClubIndex($clubIndex);
		if($tempClubObject){
			return $tempClubObject->tmcclubshortname;
		} else {
			return TmcClub::CONST_TMC_CLUB_DEFAULT;
		}
	}
	
	public function getPhoneNumber(){
		return $this->phonenumber;
	}
	
	public function getEmail(){
		return $this->email;
	}
	
	
	public function updateLastRegDateUpToDate(){
		$this->updateCertainDate(Guest::CONST_FIELD_LASTREGDATE, date("Y-n-j"));
	}
	
	public function updateLastActivityDateUpToDate(){
		$this->updateCertainDate(Guest::CONST_FIELD_LASTACTDATE, date("Y-n-j"));
	}
	
	private function updateCertainDate($dateFieldName, $dateString){
		$mysqlUtil = MysqlUtil::getInstance();
		
		$sqlSentenceUpdate = "UPDATE ".Guest::CONST_TABLE_NAME." SET `".$dateFieldName."`='".$dateString."' WHERE `".Guest::CONST_FIELD_GUESTID."`='".$this->getGuestId()."';";
		
		$mysqlUtil->queryGetResult($sqlSentenceUpdate);
	}
	
	public function getFullPrintedContent(){
		$returnString = "Guest info should be displayee here!!!";
	}
}
?>