<?php
	class SqlCfg{
		
		const CONST_SQL_HOST = "localhost";
		const CONST_PORT_NO = "3306";
		const CONST_SQL_USER_NAME = "ekunjia";
		const CONST_SQL_PASSWORD = "123456";
		const CONST_DB_NAME_HROPTMC = "hroptmc";
		
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