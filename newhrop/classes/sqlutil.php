<?php
/**
 *
 */
class SqlUtil
{
  const SQL_HOST = "localhost";
  const SQL_DB_NAME = "newhrop";
  const SQL_USER_NAME = "ekunjia";
  const SQL_PASSWORD = "123456";

  public static function getSqlConnection(){
			$sqlLink = mysql_connect(SqlUtil::SQL_HOST, SqlUtil::SQL_USER_NAME, SqlUtil::SQL_PASSWORD);

			if(!$sqlLink){
				die("Get mysql connection failure!!! At line ".__LINE__.", in file ".__FILE__);
			}

			if(!mysql_select_db(SqlUtil::SQL_DB_NAME, $sqlLink)){
				die("Use database failure!!! At line ".__LINE__.", in file ".__FILE__);
			}
			
			mysql_query("SET NAMES UTF8");

			return $sqlLink;
		}
}

?>
