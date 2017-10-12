<?php
function __autoload($class){
	if(file_exists("classes/".strtolower($class).".php")){
		require_once("classes/".strtolower($class).".php");
	} else if(file_exists("../classes/".strtolower($class).".php")){
		require_once("../classes/".strtolower($class).".php");
	}
}
?>