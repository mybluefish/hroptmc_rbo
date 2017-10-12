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

  define("INDEX_PAGE_NO", "4");
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
  	<script type="text/javascript" src="js/jquery_extend.js"></script>
</head>
<body id="mainBody">
	<?php
		showLogoAndStatus(isset($_SESSION["USERNAME"]), isset($_SESSION["USERNAME"])?$_SESSION["USERNAME"]:null, INDEX_PAGE_NO, true);

		if(isset($_SESSION["USERNAME"])){
	?>
	<div id="memberContainer">
	<?php
		$memberMgmt = MemberManager::getInstance($_SESSION["CLUBID"], "memberContainer");
		$memberMgmt->drawToPage("membersTable", "memberHeader", "memberLine", "memberInvalid", "happyBirthday");
	?>
	</div>

	<?php showFootInfo(); ?>
		<script type="text/javascript">
		var componentsArray = new Array("fixedMenuContainer", "footBar");

		$(document).ready(function(){
			setToFoot("footBar", "memberContainer", false);
			resizeComponents(componentsArray, ($("#mainAgendasTable") != undefined) ? $("#mainAgendasTable").width() : 0);
		});

		$(window).resize(function(){
			setToFoot("footBar", "memberContainer", false);
			resizeComponents(componentsArray, ($("#mainAgendasTable") != undefined) ? $("#mainAgendasTable").width() : 0);
		});
	</script>
	<?php
		} else {
			$ACTION_VALUE = "login";

			$USERNAME_ID = "userName"."_".$ACTION_VALUE;
			$PASSWORD_ID = "password"."_".$ACTION_VALUE;
			$PASSWORD_MD5_ID = "passwordMD5"."_".$ACTION_VALUE;
			$LOGIN_FORM_ID = "loginForm"."_".$ACTION_VALUE;

			printLoginPrompt(basename(__FILE__), $USERNAME_ID, $PASSWORD_ID, $PASSWORD_MD5_ID, $LOGIN_FORM_ID, $ACTION_VALUE);
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
<?php
		}
	?>


</body>
</html>
