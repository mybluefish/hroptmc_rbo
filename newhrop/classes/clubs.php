<?php
class Clubs{
	const TMC_CLUB_DEFAULT = "Other";
	
	private $toastMasterClubList = array();
	
	private static $instance;
	
	private function __construct(){
		$this->toastMasterClubList = $this->getToastMastersClubListFromDatabase();
	}
	
	public static function getInstance(){
		if(!isset(Clubs::$instance)){
			Clubs::$instance = new Clubs();
		}
		return Clubs::$instance;
	}
	
	private function getToastMastersClubListFromDatabase(){
		$toastMastersClubList = array();
		$sqlLink = SqlUtil::getSqlConnection();
		$sqlSentence = "SELECT * FROM ".ToastMastersClub::TABLE_NAME.";";
		$queryObject = mysql_query($sqlSentence, $sqlLink);
		
		if(!$queryObject){
			return $toastMastersClubList;
		}
		
		if(mysql_num_rows($queryObject) == 0){
			return $toastMastersClubList;
		}
		
		while($fetchedDatabaseArrays = mysql_fetch_array($queryObject)){
			$singleToastMastersClubItem = new ToastMastersClub($fetchedDatabaseArrays);
			array_push($toastMastersClubList, $singleToastMastersClubItem);
		}
		
		return $toastMastersClubList;
	}
	
	public function getClubProperty($index, $propertyName){
		if($index < 0 || $index > count($this->toastMasterClubList)){
			return Clubs::TMC_CLUB_DEFAULT;
		} else {
			$singleToastMastersClubItem = $this->toastMasterClubList[$index];
			return $singleToastMastersClubItem->{$propertyName};
		}
	}
	
	
	public function getClubObjByClubIndex($clubIndex, $propertyName){
		for($i = 0; $i < count($this->toastMasterClubList); $i++){
			$targetClubIndex = $this->toastMasterClubList[$i]->tmcclubindex;
			if($clubIndex == $targetClubIndex){
				return $this->toastMasterClubList[$i]->{$propertyName};
			}
		}
	}
	
}
?>