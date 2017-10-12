<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
  require_once 'config/bannercfg.php';
  require_once 'config/admincfg.php';
  require_once 'functions/functions.php';
  require_once 'config/autoload.php';
  require_once 'functions/view_funcs.php';
  
  define("INDEX_PAGE_NO", "2");
  define("ROLE_RULES_CFG", "data/rolerules.xml");
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
		
		$doc = new DOMDocument();
		$doc->load(ROLE_RULES_CFG);
		
		$roleShortNames;
		$roleNames;
		$roleDutys;
		
		$roles = $doc->getElementsByTagName("Role");
		
		foreach($roles as $key => $singleRole){
			$shortNames = $singleRole->getElementsByTagName("ShortName");
			$roleShortNames[$key] = $shortNames->item(0)->nodeValue;
		}
		
		$colorID = array("evenLine", "oddLine");
	?>
	
	<div id='roleRulesNavigator'>
	<?php 
		echo "<table id='roleRulesNavigatorTable'>";
		echo "<tr><td id='roleList'>Role List</td></tr>";
		foreach($roleShortNames as $key => $shortName){
			echo "<tr><td id='".$colorID[$key % 2]."'>";
			echo "<a href='".basename(__FILE__)."#".$shortName."'>".$shortName."</a><br / >";
			echo "</td></tr>";
		}
		echo "</table>";
	?>
	</div>
	
	<div id='roleRulesContent'>
	<?php 
		foreach($roles as $key => $singleRole){
			$names = $singleRole->getElementsByTagName("Name");
			$dutys = $singleRole->getElementsByTagName("Duty");
			echo "<div id='role".$key."' class='singleRole' onMouseOver=\"singleRoleContentAction('role".$key."', 'roleIntro".$key."', true)\" 
  				onMouseOut=\"singleRoleContentAction('role".$key."', 'roleIntro".$key."', false)\" onClick=\"clickRoleIntro('role' , 'roleIntro', ".$key.", ".count($roleShortNames).")\">";
			echo "<input type='hidden' id='roleIntro".$key."' value='0' />";
			echo "<a name='".$roleShortNames[$key]."'>".$names->item(0)->nodeValue."</a>";
			echo "<br /><pre id='rolePre".$key."'>";
			echo $dutys->item(0)->nodeValue;
			echo "</pre></div>";
		}
	?>
	</div>
	
	<div id='backToTop'>
	<a href="javascript:void(0)" onClick="setToTop()">[&nbsp;Top&nbsp;]</a>
	</div>
	<?php 
		showFootInfo();
	?>
		
	<script type="text/javascript">
		var componentsArray = new Array("fixedMenuContainer",  "footBar");

		$(document).ready(function(){
			setToFoot("footBar", "roleRulesContent", true);
			resizeComponents(componentsArray, $(window).width() > 1310 ? $(window).width() : 1310);
			setBackToTopPosition("backToTop");
		});

		$(window).resize(function(){
			setToFoot("footBar", "roleRulesContent", true);
			resizeComponents(componentsArray, $(window).width() > 1310 ? $(window).width() : 1310);
			setBackToTopPosition("backToTop");
		});

		$(window).scroll(function(){
			setBackToTopPosition("backToTop");
		});
	</script>
</body>
</html>