<?php

class RegUser{
	private $clubid;
	private $username;
	private $pswd;
	private $adminlevel;
	private $onetimeurl;
	private $specialassigned;
	private $toadminlevel;
	private $validdate;
	private $expiredate;
	
	private $names = array("clubid", "username", "pswd", "adminlevel", "onetimeurl", "specialassigned", "toadminlevel", "validdate", "expiredate");
	
	const SPECIAL_ASSIGNED_YES = 1;
	const SPECIAL_ASSIGNED_NO = 0;
	
	const CONST_DB_NAME = "users";
	
	const CONST_FIELD_CLUBID = "clubid";
	const CONST_FIELD_USERNAME = "username";
	
	public static function isSpecialAssigned($clubId){
		$sqlLink = getMysqlConnection(CONST_MYSQL_HOST, CONST_MYSQL_USER_NAME, CONST_MYSQL_PASSWORD, CONST_DB_NAME_HROPTMC);
		
// 		$fetchObject = SqlUtil::
	}
	
	public static function caculateCurrentAdminLevel($clubID){
		return RegUser::caculateAdminLevel(date("Y-m-d"), $clubID);
	}
	
	public static function caculateAdminLevel($date, $clubID){
		global $officerTableKeys;
		
		$targetAdminLevel = Admin::CONST_ADMIN_LEVEL_NA;
		
		$sqlLink = getMysqlConnection(CONST_MYSQL_HOST, CONST_MYSQL_USER_NAME, CONST_MYSQL_PASSWORD, CONST_DB_NAME_HROPTMC);
		
		if(!Member::isGivenIdValidMember($clubID, $sqlLink) && $clubID != Admin::GRANT_ADMIN_CLUB_ID){
			$targetAdminLevel = Admin::CONST_ADMIN_LEVEL_NA;
		} elseif($clubID == Admin::GRANT_ADMIN_CLUB_ID){
			$targetAdminLevel = Admin::CONST_ADMIN_LEVEL_SUPER_GRANT_ADMIN;
		} else {
			$fetchObject = SqlUtil::getFetchObjectByGivenField(SqlUtil::USERS_FIELD_NAME_CLUBID, $clubID, SqlUtil::DB_NAME_USERS, $sqlLink);
			
			if($fetchObject){
				$specialAssignedTab = $fetchObject->{SqlUtil::USERS_FIELD_NAME_SPECIALASSIGNED};
				
				if($specialAssignedTab == RegUser::SPECIAL_ASSIGNED_YES){
					$validDate = $specialAssignedTab = $fetchObject->{SqlUtil::USERS_FIELD_NAME_VALIDDATE};
					$expireDate = $specialAssignedTab = $fetchObject->{SqlUtil::USERS_FIELD_NAME_EXPIREDATE};
			
					if(isset($validDate) && ($validDate != "") && ($validDate != null) && isset($expireDate) && ($expireDate != "") && ($expireDate != null) && ($expireDate >= $validDate)){
						if($date < $validDate){
							if($officerKey = Officer::isInGivenOfficer($date, $clubID)){
								$targetAdminLevel = Officer::getDefaultAdminLevel($officerKey);
							} else {
								$targetAdminLevel = Admin::CONST_ADMIN_LEVEL_ONESELF;
							}
						} elseif($date > $expireDate){
							if(SqlUtil::updateDatabaseByClubID(array(SqlUtil::USERS_FIELD_NAME_SPECIALASSIGNED => RegUser::SPECIAL_ASSIGNED_NO), SqlUtil::USERS_FIELD_NAME_CLUBID, $clubID, SqlUtil::DB_NAME_USERS, $sqlLink)){
								if($officerKey = Officer::isInGivenOfficer($date, $clubID)){
									echo "Officer Key: ".$officerKey."<br />";
									$targetAdminLevel = Officer::getDefaultAdminLevel($officerKey);
								} else {
									$targetAdminLevel = Admin::CONST_ADMIN_LEVEL_ONESELF;
								}
							}
						} elseif($date >= $validDate  && $date <= $expireDate) {
							$targetAdminLevel = $specialAssignedTab = $fetchObject->{SqlUtil::USERS_FIELD_NAME_TOADMINLEVEL};
						}
					} else {
						//Wrong Format
					}
			
				} elseif ($specialAssignedTab == RegUser::SPECIAL_ASSIGNED_NO){
					if($officerKey = Officer::isInGivenOfficer($date, $clubID)){
						$targetAdminLevel = Officer::getDefaultAdminLevel($officerKey);
					} else {
						$targetAdminLevel = Admin::CONST_ADMIN_LEVEL_ONESELF;
					}
				} else {
					//Error Format of Special Assigned in datebase
				}
					
			} else {
				// Not in the users table, register first
			}
		}
		
		return $targetAdminLevel;
	}
}

?>