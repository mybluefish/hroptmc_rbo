<?php

class Admin{
	// Normal Members Default
	const CONST_ADMIN_LEVEL_NA = 0;  // Default
	const CONST_ADMIN_LEVEL_ONESELF = 1;  // Default
	
	// Officers Default, can be assigned to normal members
	const CONST_ADMIN_LEVEL_NORMAL_ADMIN = 2;  // Default
	const CONST_ADMIN_LEVEL_SUPER_ADMIN = 3;  //
	
	// Can be assigned to any member, only by me: 4 ^_^
	const CONST_ADMIN_LEVEL_GRANT_ADMIN = 4;  // NOT OPEN YET
	
	// Can noly be assigned to me: 4 ^_^
	const CONST_ADMIN_LEVEL_SUPER_GRANT_ADMIN = 5;
	
	// My Club ID
	const GRANT_ADMIN_CLUB_ID = 4;
}
?>