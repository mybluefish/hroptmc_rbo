<?php

class Views {
	
	const WEB_SITE_TITLE = "Welcome to Nanjing HROP TMC";
	const RBO_TABLE_TITLE = "Role Booking:";
	
	public static function printWebSiteTitle(){
		echo Views::WEB_SITE_TITLE;
	}
	
	public static function printLoginNav(){
		echo '<nav class="loginFixed">';
		echo '<a href="admin/login.php" title="Login" class="inactiveLogin" target="_self">Login</a>';
		echo '<a class="inactiveLogin">|</a>';
		echo '<a href="admin/register.php" title="Register" class="inactiveLogin" target="_self">Register</a>';
		echo '</nav>';
	}
}

?>