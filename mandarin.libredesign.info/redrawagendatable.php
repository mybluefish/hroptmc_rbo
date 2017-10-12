<?php
	session_start();
	require_once("config/admincfg.php");
	require_once("common/servicecommon.php");
	require_once("functions/functions.php");
	require_once 'functions/view_funcs.php';
	require_once 'config/autoload.php';
	
	date_default_timezone_set('Asia/Shanghai');
	
	if(!isset($_POST["centralMeetingDateOrder"])){
		exit;
	}
	
	$centralMeetingDateOrder = $_POST["centralMeetingDateOrder"];

	$roles = RoleNames::getInstance();
		
	$roleRegOnlineInstance = new RoleRegOnline($centralMeetingDateOrder, $roles, "index.php");
		
	$roleRegOnlineInstance->drawToPage("mainAgendasTable", "roleNameTableClass", "rolesTableClass");
?>