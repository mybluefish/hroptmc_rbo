<?php
class LoginRecord{
	private $dateTime;
	private $userName;
	private $ipAddress;
	private $loginType;
	
	const LOG_ON_TYPE = "on";
	const LOG_OFF_TYPE = "off";
	
	function __construct($dTime, $uName, $iAddr, $logType){
		$this->dateTime = $dTime;
		$this->userName = $uName;
		$this->ipAddress = $iAddr;
		$this->loginType = $logType;
	}
	
	public function writeRecord(){
		$sqlLink = getMysqlConnection(CONST_MYSQL_HOST, CONST_MYSQL_USER_NAME, CONST_MYSQL_PASSWORD, CONST_DB_NAME_HROPTMC);
		
		$insertLoginSqlSentence = "INSERT INTO `".CONST_DB_NAME_HROPTMC."`.`".SqlUtil::DB_NAME_LOGINRECORDS."` (
 								  `".SqlUtil::LOGINRECORDS_FIELD_NAME_DATETIME."` ,
 								  `".SqlUtil::LOGINRECORDS_FIELD_NAME_USERNAME."` ,
 								  `".SqlUtil::LOGINRECORDS_FIELD_NAME_IPADDRESS."` ,
								  `".SqlUtil::LOGINRECORDS_FIELD_NAME_LOGINTYPE."` 
								  )
							      VALUES (
							      '".$this->dateTime."','".$this->userName."','".$this->ipAddress."','".$this->loginType."'
								  );";
		
							      		
	    mysql_query($insertLoginSqlSentence, $sqlLink) or die("ERROR: FAIL TO CREATE A NEW RECORD FOR LOGIN AND OFF!!! At line ".__LINE__." in file ".__FILE__."<br>");
	}
}
?>