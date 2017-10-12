<?php
	class SqlCfg{
		
		const CONST_SQL_HOST = "mysql1212.ixwebhosting.com";
		const CONST_PORT_NO = "3306";
		const CONST_SQL_USER_NAME = "A958688_hroptmc";
		const CONST_SQL_PASSWORD = "Hrop123";
		const CONST_DB_NAME_HROPTMC = "A958688_hroptmc";
		
		public static function getSqlConnection(){
			$sqlLink = mysql_connect(SqlCfg::CONST_SQL_HOST, SqlCfg::CONST_SQL_USER_NAME, SqlCfg::CONST_SQL_PASSWORD);
			
			if(!$sqlLink){
				die("Get mysql connection failure!!! At line ".__LINE__.", in file ".__FILE__);
			}
			
			if(!mysql_select_db(SqlCfg::CONST_DB_NAME_HROPTMC, $sqlLink)){
				die("Use database failure!!! At line ".__LINE__.", in file ".__FILE__);
			}
			
			return $sqlLink;
		} 
	}
?>