<?php
class Officers{
	private $officerItemList = array ();
	
	public function __construct(){
		$this->officerItemList = $this->getOfficerItemListFromDatabase();
	}
	
	private function getOfficerItemListFromDatabase(){
		$officerItemList = array();
		
		$sqlLink = SqlUtil::getSqlConnection();
		$sqlSentence = "SELECT * FROM ".OfficerItem::TABLE_NAME.";";
		$queryObject = mysql_query($sqlSentence, $sqlLink);
		
		
		if(!$queryObject){
			return $officerItemList;
		}
		
		if(mysql_num_rows($queryObject) == 0){
			return $officerItemList;
		}
		
		while($fetchedDatabaseArrays = mysql_fetch_array($queryObject)){
			$singleOfficerItem = new OfficerItem(true, $fetchedDatabaseArrays);
			array_push($officerItemList, $singleOfficerItem);
		}
		
		return $officerItemList;
	}
	
	public static function getAllOfficersListInHistory(){
		return new Officers();
	}
	
	
	public static function getOfficerItemInstanceByDate($date){
		return OfficerItem::getOfficerItemInstanceByDate($date);
	}
}
?>