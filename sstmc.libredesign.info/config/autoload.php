<?php
function __autoload($class){
	require_once("classes/".strtolower($class).".php");
}
?>