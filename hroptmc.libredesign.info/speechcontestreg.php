<?php
    session_start();

    require_once 'config/autoload.php';
    require_once 'config/admincfg.php';
    
    $contestValueArray = $_POST["contestValueArray"];
    
    // Error code used in case of different failures
	$OK_CODE = 0;
    $ERROR_CODE_NO_SUCH_USER_EXIT = 1;
    $ERROR_CODE_MYSQL_EXECUTE_ERROR = 2;
    
    $MEMBER_NAME_INDEX = 0;
       
    $EXPLODE_PATTERN = ":";
    
    $addordelete = array("0" => false, "1" => true);
    
    if(is_array($contestValueArray)){
        
        $dataCount = count($contestValueArray);
        
        $memberName = $contestValueArray[$MEMBER_NAME_INDEX];        
        
        $clubId = Member::getClubIDWithMemberName($memberName);

        if($clubId === false){
            echo $ERROR_CODE_NO_SUCH_USER_EXIT;
            exit;
        }
        
        $mysqlUtil = MysqlUtil::getInstance();
        
        for($index = $MEMBER_NAME_INDEX + 1; $index < $dataCount; $index++){
            $speechItemFrags = explode($EXPLODE_PATTERN, $contestValueArray[$index]);
            
            $contestIndex = $speechItemFrags[0];
            $speechDate = $speechItemFrags[1];
            $isAdd = $addordelete[$speechItemFrags[2]];
            
            $querySetence = null;
            
            $querySetence = "SELECT * FROM `".SqlUtil::DB_NAME_CONTESTRECORDS."` WHERE ".
                    SqlUtil::CONTESTRECORDS_FIELD_NAME_CLUBID."=".$clubId." AND ".SqlUtil::CONTESTRECORDS_FIELD_NAME_CONTESTINDEX."=".
                    $contestIndex." AND ".SqlUtil::CONTESTRECORDS_FIELD_NAME_CONTESTDATE."='".$speechDate."';";
            
            $queryResult = $mysqlUtil->queryGetResult($querySetence);
            
            $queryNumRows = $mysqlUtil->getQueryRowNum($queryResult);
            
            if ((($isAdd) && ($queryNumRows > 0)) || ((!$isAdd) && ($queryNumRows == 0))){
                continue;
            }
            
            if($isAdd){  
                $querySetence = "INSERT INTO  `".SqlUtil::DB_NAME_CONTESTRECORDS."` (`".
                    SqlUtil::CONTESTRECORDS_FIELD_NAME_CLUBID."`, `".SqlUtil::CONTESTRECORDS_FIELD_NAME_CONTESTINDEX."`, `".
                    SqlUtil::CONTESTRECORDS_FIELD_NAME_CONTESTDATE."`) VALUES (".$clubId.", ".$contestIndex.", '".$speechDate."');";
            } else {
                $querySetence = "DELETE FROM `".SqlUtil::DB_NAME_CONTESTRECORDS."` WHERE ".
                    SqlUtil::CONTESTRECORDS_FIELD_NAME_CLUBID."=".$clubId." AND ".SqlUtil::CONTESTRECORDS_FIELD_NAME_CONTESTINDEX."=".
                    $contestIndex." AND ".SqlUtil::CONTESTRECORDS_FIELD_NAME_CONTESTDATE."='".$speechDate."';";
            }
            
            if($querySetence != null) {
                if(!$mysqlUtil->queryGetResult($querySetence)){
                    echo $ERROR_CODE_MYSQL_EXECUTE_ERROR;
                    exit;
                }
            }
        }
        
        echo $OK_CODE;
    }
?>