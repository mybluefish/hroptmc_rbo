<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php
	require_once("config/autoload.php");
  	require_once("config/bannercfg.php");
  	require_once("config/admincfg.php");
  	require_once("functions/functions.php");
  	require_once 'functions/view_funcs.php';
  	require_once("common/servicecommon.php");
  
  	date_default_timezone_set('Asia/Shanghai');
  	
  	define("INDEX_PAGE_NO", "1");
  
  	// To set page index, only shows two weeks in the past or five weeks in the future
  	$showIndex;
  
  	if(isset($_GET["id"])){
  		$showIndex = $_GET["id"];
  	}
  
	if(!isset($showIndex) || $showIndex < RoleRegOnline::CENTRAL_MEETING_ORDER - RoleRegOnline::MAX_LEFT_TO_CENTRAL_MEETING_ORDER
  		|| $showIndex > RoleRegOnline::MAX_RIGHT_TO_CENTRAL_MEETING_ORDER - RoleRegOnline::CENTRAL_MEETING_ORDER){
  		$showIndex = 0;
  	}
 ?>
 
<head>
 	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echoTitle();?></title>
	<link rel="icon" href="img/favicon.ico" />
  	<link rel="stylesheet" type="text/css" href="css/mainStyle.css">
  	<script type="text/javascript" src="js/mainjs.js"></script>
  	<script type="text/javascript" src="js/jquery.js"></script>
  	<script type="text/javascript" src="js/json2.js"></script>
  	
  	<link rel="stylesheet" type="text/css" href="css/formly.css">
  	<script type="text/javascript" src="js/formly.js"></script>
  	<script type="text/javascript">

$(document).ready(function(e) {
    $("#regPrompt").formly();
});
</script>
</head>
<body id="mainBody">
	
	<!-- Logos and status, menus  -->
	<?php 
		showLogoAndStatus(isset($_SESSION["USERNAME"]), isset($_SESSION["USERNAME"])?$_SESSION["USERNAME"]:null, INDEX_PAGE_NO, $showIndex == 0);
	?>
	
	<!-- Main page container, main content of main page -->
	<div id="mainPageContainer">
	<?php 
		$roles = RoleNames::getInstance();
		
		$roleRegOnlineInstance = new RoleRegOnline($showIndex, $roles, basename(__FILE__));
		
		$roleRegOnlineInstance->drawToPage("mainAgendasTable", "roleNameTableClass", "rolesTableClass");
	?>
	</div>
	
	<!-- Foot info shown here  -->
	<?php 
		showFootInfo();
	?>
	
	<!-- This is the div for regPrompt -->
	<div id="regPrompt"></div>
	
<!-- 	<div id="noticeShowUp"></div> -->
	
	<script type="text/javascript">
		var componentsArray = new Array("fixedMenuContainer", "footBar");

		$(document).ready(function(){
			setToFoot("footBar", "navigatorBar", false);
			resizeComponents(componentsArray, ($("#mainAgendasTable") != undefined) ? $("#mainAgendasTable").width() : 0);
 			//$("#noticeShowUp").html("Website will be updated during 11:00 PM May 22nd, 2014 - 1:00 AM May 23rd, 2014");
 			//$("#noticeShowUp").css({"left": 20, "top": 10, "color": "red"});
		});

		$(window).resize(function(){
			setToFoot("footBar", "navigatorBar", false);
			resizeComponents(componentsArray, ($("#mainAgendasTable") != undefined) ? $("#mainAgendasTable").width() : 0);
		});
	</script>
	
	</body>
</html>