<?php session_start(); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<?php 
require_once("config/autoload.php");
date_default_timezone_set('Asia/Shanghai');

$startIndex = 0;
if(isset($_GET["id"])){
	$startIndex = $_GET["id"];
}
?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php Views::printWebSiteTitle(); ?></title>

<link rel="stylesheet" type="text/css" href="style/mainstyle.css">
<link rel="icon" href="images/favicon.ico" />
<script type="text/javascript" src="script/jquery-2.1.4.min.js"></script>

<link rel="stylesheet" type="text/css" href="style/formly.css">
<script type="text/javascript" src="script/formly.js"></script>
  	
	<script type="text/javascript">
		$(document).ready(function(e) {
    		$("#regPrompt").formly();
		});
	</script>
</head>

<body id="homeBody">

<div id="headerWrap">
    <div class="innerWrap">
    </div>
</div>

<div id="navigatorWrap">
    <div class="innerWrap" id="navigatorList">
    <?php
	$navObject = new Navigator(1, 0, '.');
	$navObject->printNavigator();
	
	Views::printLoginNav();
    ?>
    </div>
</div>

<div id="advertisementTopWrap">
    <div class="innerWrap">
    <?php
	echo '这里将是广告展示区域！';
	?>
    </div>
</div>

<div id="bodayWrap">
    <div class="innerWrap">
    <?php
		$roleNamesObj = new RoleNames();
		$rboObj = new RBO($startIndex, $roleNamesObj, "regPrompt");
		$rboObj->printRboTable(Views::RBO_TABLE_TITLE);
	?>
    </div>
</div>

<div id="advertisementMiddleWrap">
    <div class="innerWrap">
    <?php
	echo '这里也将是广告展示区域！';
	?>
    </div>
</div>

<div id="tmcNewsWrap">
    <div class="innerWrap">
    <?php
	echo '这里展示新闻！～～～～～～～～～～～～～～～～～～';
	?>
    </div>
</div>

<div id="advertisementWrap">
    <div class="innerWrap">
    <?php
	echo '这里也将是广告展示区域！';
	?>
    </div>
</div>

<div id="footerWrap">
    <div class="innerWrap">
    </div>
</div>

<!-- This is the div for regPrompt -->
<div id="regPrompt"></div>
    
</body>
</html>