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

  date_default_timezone_set('Asia/Shanghai');

  define("INDEX_PAGE_NO", "3");





?>
<html>
<head>
 	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echoTitle();?></title>
	<link rel="stylesheet" type="text/css" href="css/mainStyle.css">
	<link rel="stylesheet" type="text/css" href="css/position.css">
	<script type="text/javascript" src="js/mainjs.js"></script>
	<script type="text/javascript" src="js/md5.js"></script>
  	<script type="text/javascript" src="js/jquery.js"></script>
</head>
<body id="mainBody">
	<?php
		showLogoAndStatus(isset($_SESSION["USERNAME"]), isset($_SESSION["USERNAME"])?$_SESSION["USERNAME"]:null, INDEX_PAGE_NO, true);

		if(isset($_SESSION["USERNAME"])){
			$officerInstance = Officer::getCurrentOfficer();

			if($officerInstance->isOfficer($_SESSION["CLUBID"])){
				$meetingDateSelected = MeetingDate::getCurrentMeetingDate();
				$meetingDateSelectedString = $meetingDateSelected->toString();

				if(isset($_POST[ConstantValue::CONST_FIELD_AGENDA_MANAGE_DATE_SELECT])){
					$meetingDateSelectedString = $_POST[ConstantValue::CONST_FIELD_AGENDA_MANAGE_DATE_SELECT];
					$meetingDateSelected = MeetingDate::getMeetingDateFromTimeStamp(strtotime($meetingDateSelectedString));
				}

				$roleNames = RoleNames::getInstance();

				echo "\n\n";
				echo "<div class='dataSelectDiv'>";
					echo "<form id='meetingDateSelectionForm' action='manageagendas.php' method='post'>";
					echo "Meeting Date:<br>";
					echo "<select style='width:120px' name='".ConstantValue::CONST_FIELD_AGENDA_MANAGE_DATE_SELECT."' onChange='meetingDateChangeOfAgendaManage(\"meetingDateSelectionForm\")'>";

					$meetingDateArray = RegularMeeting::getMeetingDateList();
					if(is_array($meetingDateArray)){
						foreach ($meetingDateArray as $meetingDateValue){
							echo "<option value='".$meetingDateValue."' ".(($meetingDateValue == $meetingDateSelectedString)?"selected":"").">".$meetingDateValue."</option>";
						}
					} else {
						echo "<option value='".$meetingDateSelectedString."'>".$meetingDateSelectedString."</option>";
					}
					echo "</select>";
					echo "</form>";
				echo "</div>";

				echo "\n\n";
				/**
				 * This area is for painting agenda updating and closing table
				 */
				echo "<div class='agendaUpdateDiv'>";
					echo "<form id='agendaUpdateForm' action='agendamgmtupdate.php' method='post'>";
						echo "<input type='hidden' id='".ConstantValue::CONST_MEETING_DATE_HIDDEN_ID."' name='".ConstantValue::CONST_MEETING_DATE_HIDDEN_NAME."' value='".$meetingDateSelectedString."' />";
						$regularMeeting = new RegularMeeting($roleNames, $meetingDateSelected, true);
						$regularMeeting->drawUpdateAndCloseTable("agendaUpdateForm", "updateAgenda", "closeReopen");
					echo "</form>";
				echo "</div>";
			} else {
				echo "Sorry, you are not a officer, you can not manage the agenda.";
			}

		} else {
			$ACTION_VALUE = "login";

			$USERNAME_ID = "userName"."_".$ACTION_VALUE;
			$PASSWORD_ID = "password"."_".$ACTION_VALUE;
			$PASSWORD_MD5_ID = "passwordMD5"."_".$ACTION_VALUE;
			$LOGIN_FORM_ID = "loginForm"."_".$ACTION_VALUE;

			printLoginPrompt(basename(__FILE__), $USERNAME_ID, $PASSWORD_ID, $PASSWORD_MD5_ID, $LOGIN_FORM_ID, $ACTION_VALUE);
	?>
<script type="text/javascript">
	$('#loginForm_login').submit(function() {
		return checkBeforeLogin("userName_login", "password_login", "passwordMD5_login");
	});
</script>
<?php
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
</body>
</html>
