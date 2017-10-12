<?php
  class OfficerManager{
    private $myClubID;
    private $containerID;

    private $officerRecordList = array();

    public function __construct($mClubID, $conID){
      global $officerTableKeys;

      $this->myClubID = $mClubID;
      $this->containerID = $conID;

      $mySqlUtilInst = MysqlUtil::getInstance();

      $sqlQuerySentence = "select * from ".Officer::CONST_TABLE_NAME." ORDER BY ".Officer::CONST_FIELD_VALIDDATE." ASC";

      $queryReslut = $mySqlUtilInst->queryGetResult($sqlQuerySentence);

      while ($officerRecord = $mySqlUtilInst->getQueryResultArray($queryReslut)) {
        $officerRecordFiltered;
        foreach ($officerTableKeys as $key => $value) {
          if(array_key_exists($value, $officerRecord)){
  					$officerRecordFiltered[$value] = $officerRecord[$value];
  				}
        }

        array_push($this->officerRecordList, new Officer($officerRecordFiltered));
      }
    }

    public function drawToPage($tableID){
      global $officerTableKeys;
      echo "<table id='".$tableID."'>";
      // print table header
      echo "<tr>";
      foreach ($officerTableKeys as $key => $value) {
        echo "<td>".$value."</td>";
      }
      echo "</tr>";

      foreach ($this->officerRecordList as $key => $value) {
        echo "<tr>";
        $nameArray = $value->getAttributeArray();
        foreach ($nameArray as $nameArrayKey => $nameArrayValue) {
          if($value->isOfficerRole($nameArrayValue)){
            echo "<td>(".$value->{$nameArrayValue}.")".Member::getMemberFieldByCludId($value->{$nameArrayValue}, SqlUtil::MEMBERS_FIELD_NAME_MEMBERNAME)."</td>";

          } else {
            echo "<td>".$value->{$nameArrayValue}."</td>";
          }
        }
        echo "</tr>";
      }
      echo "</table>";
    }
  }
 ?>
