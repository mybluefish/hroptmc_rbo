<?php
	$memberTableKeys = array("ClubID", "MemberID", "MemberName", "ValidStatus","LevelCC", "LevelCL", "ChineseName", "Email",
			"PhoneNo", "QQNumber", "WeiboID", "Birthday", "CurrentCC", "CurrentCL");
	
	$officerTableKeys = array("ValidDate", "ExpireDate", "President", "VPE", "VPM", "VPPR", "SAA", "Treasurer", "Secretary");
	
	$usersTableKeys = array("clubid", "username", "pswd", "adminlevel", "onetimeurl", "specialassigned", "toadminlevel", "validdate", "expiredate");
	
	$HOSTNAME = "http://localhost/rolebookonline/";
		
	$ROLE_NAME_CFG = "config/roleName.cfg";
?>