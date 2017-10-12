<?php

class ContestManager
{
    private static $instance;
    
    private $contestItems = array();
    
    private function __construct(){
        $todayDate = date("Y-m-d");
    
        $mysqlUtil = MysqlUtil::getInstance();
    
        $sqlOfContestList = $this->constructSqlOfContestList($todayDate);
    
        $contestListQuery = $mysqlUtil->queryGetResult($sqlOfContestList);
    
        while($contestListSingleItem = $mysqlUtil->getQueryResultObject($contestListQuery)){
            $contestIndex = $contestListSingleItem->{SqlUtil::CONTESTPUBLISH_FIELD_NAME_CONTESTINDEX};
            $contestDate = $contestListSingleItem->{SqlUtil::CONTESTPUBLISH_FIELD_NAME_CONTESTDATE};
            $sqlOfContestContentByIndex = $this->constructSqlOfContestContent($contestIndex);
            
            $contestContentQuery = $mysqlUtil->queryGetResult($sqlOfContestContentByIndex);
            $contestContentObject = $mysqlUtil->getQueryResultObject($contestContentQuery);
            $contestTitle = $contestContentObject->{SqlUtil::CONTESTMETADATA_FIELD_NAME_CONTESTTITLE};
            $contesLanguage = $contestContentObject->{SqlUtil::CONTESTMETADATA_FIELD_NAME_LANGUAGE};
            
//             echo $contestIndex."-".$contestTitle."-".$contesLanguage."-".$contestDate."<br>";
            array_push($this->contestItems, Contest::getContestInstance($contestIndex, $contestTitle, $contesLanguage, $contestDate));
        }
    }
    
    public static function getInstance(){
        if(!isset(ContestManager::$instance)){
            ContestManager::$instance = new ContestManager();
        }
        return ContestManager::$instance;
    }
    
    public function drawContestPage(){
        if(count($this->contestItems) == 0) {
            echo "Contest Season is not started yet! Please attend regular meeting.";
        } else {
            $this->drawContestRegTable();
            $this->drawStatisticsTable();
            $this->drawNameListTable(true);
        }
    }
    
    private function drawContestRegTable(){
        echo "<div id='regContestInputContainer'>";
        
        echo "<label class='tableTitle'>Speech Contest Registerion:</label><br>";
        
        echo "<table cellpadding='10px'>";
        
        echo "<tr><td>Your Name:</td><td><input type='text' id='regContestNameInput' size='40px'></td></tr>\n";
        
        echo "<tr><td valign='top'>Contest to Reg:</td><td>";
            
        for($index = 0; $index < count($this->contestItems); $index++){
            echo "<input type='checkbox' name='speechRegList' id='speechRegList_".$index."' value='".$this->contestItems[$index]->contestIndex.":".$this->contestItems[$index]->speechDate."'>&nbsp;".
                 $this->contestItems[$index]->contestTitle."&nbsp;(".$this->contestItems[$index]->speechDate.")<br>\n";
        }
        
        echo "</td></tr>\n";

        echo "<tr><td>&nbsp;</td><td align='right'><input type='button' value='Submit' onClick='submitContestReg(\"regContestInputContainer\",\"regContestNameInput\",\"speechRegList_\", ".count($this->contestItems).");' /></td></tr>\n";
        
        echo "</table></div>";
    }
    
    private function drawStatisticsTable(){
        
        $mysqlUtil = MysqlUtil::getInstance();
        $numRecords = array();
        $totalNum = 0;
        
        echo "<div id='contestStatisticsContainer'>";
        
        echo "<label class='tableTitle'>Contest Regisition Statistics:</label><br>";
        
        for($index = 0; $index < count($this->contestItems); $index++){
            $sqlOfContestRecords = $this->constructSqlOfContestRecords($this->contestItems[$index]->contestIndex, $this->contestItems[$index]->speechDate);
            
            $queryResult = $mysqlUtil->queryGetResult($sqlOfContestRecords);
            
            $queryNumRows = $mysqlUtil->getQueryRowNum($queryResult);
            
            $totalNum += $queryNumRows;
            
            array_push($numRecords, $queryNumRows);
        }
        echo "<table cellpadding='10px' width='1200px'>";
        
        echo "<tr><td colspan='2' >Until now, total ".$totalNum." people * times have successfully regisered speech contest.</td></tr>";
        
        for($index = 0; $index < count($this->contestItems); $index++){
            echo "<tr><td width='180px' align='center'>".$this->contestItems[$index]->contestTitle."<br>(".$this->contestItems[$index]->speechDate.")</td>";
            echo "<td><div
                style='background-color:#00FFFF; width:".($totalNum == 0 ? 0 : 100*$numRecords[$index]/$totalNum)."%'>".$numRecords[$index]."&nbsp;(".($totalNum == 0 ? 0 : floor(100*$numRecords[$index]/$totalNum))."%)</div></td>";
            echo "</tr>";
        }
        
        echo "</table>";
        
        echo "</div>";
    }
    
    private function drawNameListTable($isLogIn){
        $mysqlUtil = MysqlUtil::getInstance();
        
        echo "<div id='contestNameListContainer'>";
        echo "<label class='tableTitle'>Name List for the Contestants:</label><br><br>\n";
        if(!$isLogIn){
            echo "Please <a href='login.php'>log in</a> to see the names for each contest.";
        } else {
            echo "<table cellpadding='10px' width='1200px'>";
            for($index = 0; $index < count($this->contestItems); $index++){
                $sqlOfContestRecords = $this->constructSqlOfContestRecords($this->contestItems[$index]->contestIndex, $this->contestItems[$index]->speechDate);
                
                $queryResult = $mysqlUtil->queryGetResult($sqlOfContestRecords);
                
                $eachNameListString = "";
                
                while($eachItemObject = $mysqlUtil->getQueryResultObject($queryResult)){
                    if($eachNameListString != ""){
                        $eachNameListString .= ", ";
                    }
                    
                    $clubId = $eachItemObject->{SqlUtil::CONTESTRECORDS_FIELD_NAME_CLUBID};
                    
                    $memberName = Member::getMemberFieldByCludId($clubId, SqlUtil::MEMBERS_FIELD_NAME_MEMBERNAME);
                    
                    $eachNameListString .= $memberName;
                    
                }
               
                
                if($eachNameListString == ""){
                    $eachNameListString ="-";
                }
                
                echo "<tr style='background-color:#FFFFA0'><td>".$this->contestItems[$index]->contestTitle."&nbsp;(".$this->contestItems[$index]->speechDate."):</td></tr>";
                echo "<tr><td>Names: ".$eachNameListString."</td></tr>";
            }
            echo "</table>";
        }
        echo "</div>";
    }
    
    private function constructSqlOfContestList($date){
        $sqlOfContestListString =
            "SELECT * FROM ".SqlUtil::DB_NAME_CONTESTPUBLISH." WHERE ".SqlUtil::CONTESTPUBLISH_FIELD_NAME_STARTDATE."<='".
            $date."' AND ".SqlUtil::CONTESTPUBLISH_FIELD_NAME_ENDDATE.">='".$date."'";
    
        return $sqlOfContestListString;
    }
    
    private function constructSqlOfContestContent($contestIndex){
        $sqlOfContestContestByIndex =
            "SELECT * FROM ".SqlUtil::DB_NAME_CONTESTMETADATA." WHERE ".SqlUtil::CONTESTMETADATA_FIELD_NAME_CONTESTINDEX.
            "='".$contestIndex."';";
        
        return $sqlOfContestContestByIndex;
    }
    
    private function constructSqlOfContestRecords($contestIndex, $speechDate){
        $sqlOfContestRecords = "SELECT * FROM `".SqlUtil::DB_NAME_CONTESTRECORDS."` WHERE "
            .SqlUtil::CONTESTRECORDS_FIELD_NAME_CONTESTINDEX."=".$contestIndex." AND "
            .SqlUtil::CONTESTRECORDS_FIELD_NAME_CONTESTDATE."='".$speechDate."';";
        
        return $sqlOfContestRecords;
    }
}

?>