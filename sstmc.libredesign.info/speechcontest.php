<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
	require_once 'config/autoload.php';
	require_once 'config/bannercfg.php';
	require_once 'config/admincfg.php';
	require_once 'functions/view_funcs.php';
	
	date_default_timezone_set('Asia/Shanghai');
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echoTitle();?></title>
<link rel="stylesheet" type="text/css" href="css/mainStyle.css">
<script type="text/javascript" src="js/mainjs.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
</head>

<body>
    <div id="regContestContainer">
    
    <?php
        $speechContest = ContestManager::getInstance();
        $speechContest->drawContestPage();
    ?>
    
    </div>
	

</body>
</html>