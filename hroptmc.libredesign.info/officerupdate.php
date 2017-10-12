<?php
    session_start();

    require_once 'config/admincfg.php';
    require_once 'common/servicecommon.php';
    require_once 'functions/functions.php';
    require_once 'config/autoload.php';
    require_once 'functions/view_funcs.php';

    if(isset($_SESSION["USERNAME"])){
        $clubId = $_SESSION["CLUBID"];
        $officer = Officer::getCurrentOfficer();

        if($officer->isOfficer($clubId)){
            $tag = $_POST["tag"];


            $officerArray = array(SqlUtil::OFFICERS_FIELD_NAME_PRESIDENT, SqlUtil::OFFICERS_FIELD_NAME_VPE,
                SqlUtil::OFFICERS_FIELD_NAME_VPM, SqlUtil::OFFICERS_FIELD_NAME_VPPR,
                SqlUtil::OFFICERS_FIELD_NAME_SAA, SqlUtil::OFFICERS_FIELD_NAME_TREASURER,
                SqlUtil::OFFICERS_FIELD_NAME_SECRETARY
            );

            $startDateStr = "startDate";
            $expireDateStr = "expireDate";

            if($tag == "get"){

              $value = $_POST["value"];

                $returnValue = "<form name='officerUpdateFormName' id='officerUpdateFormId'>";
                $returnValue .= "<table>";

                $returnValue .= "<tr><td align='right'><label id='".$startDateStr."_label'>Start Date:</td><td align='left'><input type='text' id='".$startDateStr."' /></td></tr>";
                $returnValue .= "<tr><td align='right'><label id='".$expireDateStr."_label'>Expire Date:</td><td align='left'><input type='text' id='".$expireDateStr."' /></td></tr>";

                foreach ($officerArray as $arrayKey => $arrayValue){
                    $returnValue .= "<tr><td align='right'><label id='".$arrayValue."_label'>".$arrayValue.": </label></td><td>";

                    $memberMgmt = MemberManager::getInstance($_SESSION["CLUBID"], "memberContainer");

                    $returnValue .= $memberMgmt->getValidMemberSelectionList($_SESSION["CLUBID"], $arrayValue, $arrayValue, null);

                    $returnValue .="</td></tr>";
                }

                $returnValue .= "<tr><td>&nbsp;</td><td align='right'><input type='button' value='Submit' onClick='submitUpdateOfficer()'/></td></tr>";

                $returnValue .="</table>";
                $returnValue .= "</form>";

                $returnValue .= "<script>
                                  \$(function(){
                                    \$('#startDate').datepicker({
                                      dateFormat: 'yy-mm-dd'
                                    });
                                    \$('#expireDate').datepicker({
                                      dateFormat: 'yy-mm-dd'
                                    });
                                  });
                                </script>";

                $officerMgrInst = new OfficerManager($_SESSION["CLUBID"], "officerRecordTableID");

                $officerMgrInst->drawToPage("officerListTableID");

                echo $returnValue;
            } elseif ($tag == "update") {

              $constructArray = array();

              // $startDateValue = $_POST[$startDateStr];
              // array_push($constructArray, $startDateValue);
              $constructArray[$officerTableKeys[0]] = $_POST[$startDateStr];

              // $expireDateValue = $_POST[$expireDateStr];
              // array_push($constructArray, $expireDateValue);
              $constructArray[$officerTableKeys[1]] = $_POST[$expireDateStr];

              foreach ($officerArray as $key => $value) {
                // $officerValue[$value] = $_POST[$value];
                // array_push($constructArray, $_POST[$value]);
                $constructArray[$value] = $_POST[$value];
              }

// echo count($constructArray);
              $officer = new Officer($constructArray);

              // echo $officer->toString();
              if($officer->addMyselfIntoRecords()){
                echo "done!";
              } else {
                echo "Error!";
              }
            }
        }
    }
?>
