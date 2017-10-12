<?php
	session_start();
	
	if(isset($_SESSION["USERNAME"])){
		echo "Welcome back ".$_SESSION["MEMBERNAME"]."!<br />";
		echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"3; URL=index.php\">";
		echo "Wait 3 seconds to redirect to main page, or click <a href=\"index.php\">here</a>";
	} else {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Login - Nanjing HROP Toastmasters Club RoleBookOnline</title>
	<script type="text/javascript" src="js/md5.js"></script>
	<script type="text/javascript" src="js/jquery.js"></script>
	<link rel="stylesheet" type="text/css" href="css/position.css" />
	
	<script type="text/javascript">
		var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
		document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3Fb5e54e73c181db445fe3e17986177cd7' type='text/javascript'%3E%3C/script%3E"));
	</script>

</head>
<body>
<?php 
	require_once 'functions/view_funcs.php';
	
	$ACTION_VALUE = "register";

	$USERNAME_ID = "userName"."_".$ACTION_VALUE;
	$PASSWORD_ID = "password"."_".$ACTION_VALUE;
	$PASSWORD_MD5_ID = "passwordMD5"."_".$ACTION_VALUE;
	$REG_FORM_ID = "regForm"."_".$ACTION_VALUE;
	
	printRegisterPrompt(basename(__FILE__), $USERNAME_ID, $PASSWORD_ID, $PASSWORD_MD5_ID, $REG_FORM_ID, $ACTION_VALUE);
?>

<script type="text/javascript">
	$('#regForm_register').submit(function() {
  		if($("#userName_register").val() == ""  || $("#password_register").val() == "" || $("#password_register2").val() == ""){
  			alert("Please fill in all the field to complete register!");
  			return false;
  		} if($("#password_register").val() != $("#password_register2").val()){
  	  		alert("Passwords are not the same in two fields! Please check that again!") 
			return false;
  		} else {
  			$("#passwordMD5_register").val(hex_md5($("#password_register").val()));
			$("#password_register").val("");
			$("#password_register2").val("");
			return true;
		}
	});
</script>
</body>
</html>
<?php
	}
?>