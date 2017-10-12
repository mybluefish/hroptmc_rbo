<?php
class CollectionBase {
	private function getRoleNameItemListFromDatabase(){
		$roleNameItemList = array();
		$sqlLink = SqlUtil::getSqlConnection();
		$sqlSentence = "SELECT * FROM ".RoleNameItem::TABLE_NAME." WHERE ".RoleNameItem::IS_SHOWN."=1 ORDER BY ".RoleNameItem::SHOW_ORDER." ASC;";
		$queryObject = mysql_query($sqlSentence, $sqlLink);
		
		if(!$queryObject){
			return $roleNameItemList;
		}
		
		if(mysql_num_rows($queryObject) == 0){
			return $roleNameItemList;
		}
		
		while($fetchedDatabaseArrays = mysql_fetch_array($queryObject)){
			$singleRoleNameItem = new RoleNameItem($fetchedDatabaseArrays);
			array_push($roleNameItemList, $singleRoleNameItem);
		}
		
		return $roleNameItemList;
	}
}
?>