<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
  require_once 'config/bannercfg.php';
  require_once 'config/admincfg.php';
  require_once 'functions/functions.php';
  require_once 'config/autoload.php';
  require_once 'functions/view_funcs.php';
  
  define("INDEX_PAGE_NO", "6");
?>
<html>
<head>
 	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echoTitle();?></title>
	<link rel="stylesheet" type="text/css" href="css/mainStyle.css">
	<script type="text/javascript" src="js/mainjs.js"></script>
  	<script type="text/javascript" src="js/jquery.js"></script>
</head>
<body id="mainBody">
	<?php
		showLogoAndStatus(isset($_SESSION["USERNAME"]), isset($_SESSION["USERNAME"])?$_SESSION["USERNAME"]:null, INDEX_PAGE_NO, true);
		echo "This function is still under construction yet!!<br />";
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
