<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
  require_once 'config/bannercfg.php';
  require_once 'config/admincfg.php';
  require_once 'common/servicecommon.php';
  require_once 'functions/functions.php';
  require_once 'config/autoload.php';
  require_once 'functions/view_funcs.php';

  define("INDEX_PAGE_NO", "7");
?>
<html>
<head>
 	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echoTitle();?></title>
	<link rel="stylesheet" type="text/css" href="css/mainStyle.css">
	<link rel="stylesheet" type="text/css" href="css/position.css">
  <link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
	<script type="text/javascript" src="js/mainjs.js"></script>
  	<script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/jquery-ui.js"></script>
  	<script type="text/javascript" src="js/md5.js"></script>
  	<script type="text/javascript" src="js/jquery_extend.js"></script>
</head>
<body id="mainBody">
	<?php
		showLogoAndStatus(isset($_SESSION["USERNAME"]), isset($_SESSION["USERNAME"])?$_SESSION["USERNAME"]:null, INDEX_PAGE_NO, true);

		if(isset($_SESSION["USERNAME"])){
		    $clubId = $_SESSION["CLUBID"];

		    $officer = Officer::getCurrentOfficer();

		    if($officer->isOfficer($clubId)){
		        echo "<div style='text-align:left'>";
		        echo "Welcome, dear ".$officer->getOfficerDutyName($clubId)." ".$officer->getCurrentOfficerName($officer->getOfficerDutyName($clubId));
		        echo "</div>";

		        $officerDuties = array("OU"=>"Officer Update", "SD"=>"Special Date", "CM"=>"Contest Management");
		        $formContainerId = "formContentCell";

		        echo "<div id='officerDuties'>";
		        echo "<table>";
		        echo "<tr>
                    <td>Officer Duties</td>
                    <td rowspan='".(count($officerDuties) + 1)."'>
                        <div id='".$formContainerId."'>";
            //===============================================================================================
            // hidden select for all officers
            // $officerArray = array(SqlUtil::OFFICERS_FIELD_NAME_PRESIDENT, SqlUtil::OFFICERS_FIELD_NAME_VPE,
            //     SqlUtil::OFFICERS_FIELD_NAME_VPM, SqlUtil::OFFICERS_FIELD_NAME_VPPR,
            //     SqlUtil::OFFICERS_FIELD_NAME_SAA, SqlUtil::OFFICERS_FIELD_NAME_TREASURER,
            //     SqlUtil::OFFICERS_FIELD_NAME_SECRETARY
            // );
            //
            // echo "<form name='officerUpdateFormName' id='officerUpdateFormId'>
            //           <table>";
            //
            // foreach ($officerArray as $arrayKey => $arrayValue){
            //   echo "<tr>
            //           <td align='right'><label id='".$arrayValue."_label'>".$arrayValue.": </label></td>
            //           <td>";
            //   $memberMgmt = MemberManager::getInstance($_SESSION["CLUBID"], "memberContainer");
            //
            //   echo $memberMgmt->getValidMemberSelectionList($_SESSION["CLUBID"], $arrayValue, $arrayValue, null);
            //
            //   echo "</td></tr>";
            // }
            // echo "<tr><td>&nbsp;</td><td align='right'><input type='button' value='Submit' onClick='submitUpdateOfficer()'/></td></tr>";
            // echo "</table></form>";
            //===============================================================================================
            echo "</div>
                    </td>
                  </tr>";

		        foreach ($officerDuties as $key => $value){
		          echo "<tr><td id='".$key."_link'><a href='javascript:void(0)' onClick='officerDutyLinkListener(\"".$key."_link\", \"".$formContainerId."\")'>".$value."</a></td></tr>";
		        }

		        echo "</table>";
		        echo "</div>";

		    } else {
		        $presidentMemberObj = Member::getMemberInstanceFromDB($officer->President);
		        echo "Sorry, only officers can operate on this page.<br> Want to join officer team? Please contact our ".RoleNames::CONST_VALUE_PRESIDENT." ".$presidentMemberObj->MemberName." (".$presidentMemberObj->Email.")";
		    }
		} else {
		    $ACTION_VALUE = "login";

		    $USERNAME_ID = "userName"."_".$ACTION_VALUE;
		    $PASSWORD_ID = "password"."_".$ACTION_VALUE;
		    $PASSWORD_MD5_ID = "passwordMD5"."_".$ACTION_VALUE;
		    $LOGIN_FORM_ID = "loginForm"."_".$ACTION_VALUE;

		    printLoginPrompt(basename(__FILE__), $USERNAME_ID, $PASSWORD_ID, $PASSWORD_MD5_ID, $LOGIN_FORM_ID, $ACTION_VALUE);
		}
	?>

	<?php
		showFootInfo();
	?>

	<script type="text/javascript">
		var componentsArray = new Array("fixedMenuContainer", "footBar");

		$(document).ready(function(){
			setToFoot("footBar", "fixedMenuContainer", false);
			resizeComponents(componentsArray, ($("#mainAgendasTable") != undefined) ? $("#mainAgendasTable").width() : 0);
		});

		$(window).resize(function(){
			setToFoot("footBar", "fixedMenuContainer", false);
			resizeComponents(componentsArray, ($("#mainAgendasTable") != undefined) ? $("#mainAgendasTable").width() : 0);
		});
	</script>

	<script type="text/javascript">
	$('#loginForm_login').submit(function() {
		return checkBeforeLogin("userName_login", "password_login", "passwordMD5_login");
	});
</script>
</body>
</html>
