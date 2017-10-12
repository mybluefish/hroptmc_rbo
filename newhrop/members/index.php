<?php session_start(); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php printWebSiteTitle(); ?></title>
<?php 
require_once("../config/autoload.php");
date_default_timezone_set('Asia/Shanghai');
?>
<link rel="stylesheet" type="text/css" href="../style/mainstyle.css">

</head>

<body id="homeBody">

<div id="headerWrap">
    <div class="innerWrap">
    </div>
</div>

<div id="navigatorWrap">
    <div class="innerWrap" id="navigatorList">
    <?php
	$navObject = new Navigator(5, 0, '..');
	$navObject->printNavigator();
	
	Views::printLoginNav();
    ?>
    </div>
</div>

<div id="advertisementTopWrap">
    <div class="innerWrap">
    </div>
</div>

<div id="bodayWrap">
    <div class="innerWrap">
    </div>
</div>

<div id="advertisementMiddleWrap">
    <div class="innerWrap">
    </div>
</div>

<div id="tmcNewsWrap">
    <div class="innerWrap">
    </div>
</div>

<div id="advertisementWrap">
    <div class="innerWrap">
    </div>
</div>

<div id="footerWrap">
    <div class="innerWrap">
    </div>
</div>

</body>
</html>

<?php 
function printWebSiteTitle(){
    echo "Welcome to Nanjing HROP TMC";
}
?>