<?php
class MysqlUtil{
	private static $mysqlUtil;

	private $mysqlLink;

	private $mysqlHost;
	private $userName;
	private $password;
	private $dataBaseName;

	private function __construct(){
		$this->mysqlHost = SqlCfg::CONST_SQL_HOST;
		$this->userName = SqlCfg::CONST_SQL_USER_NAME;
		$this->password = SqlCfg::CONST_SQL_PASSWORD;
		$this->dataBaseName = SqlCfg::CONST_DB_NAME_HROPTMC;

		$this->initMysqlConnection();
	}

	private function initMysqlConnection(){
		$this->mysqlLink = mysql_connect($this->mysqlHost, $this->userName, $this->password)
			or die("ERROR: Fail to connect to databaseAt line ".__LINE__." in file ".__FILE__."<br>");

		mysql_select_db($this->dataBaseName)
			or die("ERROR: Fail to select the database. At line ".__LINE__." in file ".__FILE__."<br>");

		mysql_query("SET NAMES UTF8");
	}


	public static function getInstance(){
		if(!isset(MysqlUtil::$mysqlUtil)){
			MysqlUtil::$mysqlUtil = new MysqlUtil();
		}

		return MySqlUtil::$mysqlUtil;
	}



	public function queryGetResult($sentence){
		mysql_query("SET NAMES UTF8");
		if(!is_resource($this->mysqlLink)){
			$this->initMysqlConnection();
		}
		$queryResult = mysql_query($sentence, $this->mysqlLink);

		return $queryResult;
	}

	public function getQueryRowNum($query){
		$queryNum = 0;

		if(is_resource($query)){
			$queryNum = mysql_num_rows($query);
		}

		return $queryNum;
	}

	public function getQueryResultArray($query){
		$resultArray = false;

		if(is_resource($query)){
			$resultArray = mysql_fetch_array($query);
		}

		return $resultArray;
	}

	public function getQueryResultObject($query){
		$resultObject = false;

		if(is_resource($query)){
			$resultObject = mysql_fetch_object($query);
		}

		return $resultObject;
	}

}
?>
