<?php
	session_start();
	date_default_timezone_set('Asia/Shanghai');

	if(isset($_SESSION["USERNAME"])){
		// header('Content-Type: text/html; charset=utf-8');
		echo "欢迎回来".$_SESSION["MEMBERNAME"]."！<br />";
		echo "<META HTTP-EQUIV=this\"Refresh\" CONTENT=\"1; URL=index.php\">";
		echo "1秒钟后自动返回主页，或者点击<a href=\"index.php\">这里</a>返回";
	} else {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>登录 - 南京第一中文演讲俱乐部在线角色预订</title>
	<script type="text/javascript" src="js/md5.js"></script>
	<script type="text/javascript" src="js/mainjs.js"></script>
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

		$ACTION_VALUE = "login";

		$USERNAME_ID = "userName"."_".$ACTION_VALUE;
		$PASSWORD_ID = "password"."_".$ACTION_VALUE;
		$PASSWORD_MD5_ID = "passwordMD5"."_".$ACTION_VALUE;
		$LOGIN_FORM_ID = "loginForm"."_".$ACTION_VALUE;

		printLoginPrompt(basename(__FILE__), $USERNAME_ID, $PASSWORD_ID, $PASSWORD_MD5_ID, $LOGIN_FORM_ID, $ACTION_VALUE);
	?>

<script type="text/javascript">
	$('#loginForm_login').submit(function() {
		return checkBeforeLogin("userName_login", "password_login", "passwordMD5_login");
	});
</script>
</body>
</html>
<?php
	}
?>
