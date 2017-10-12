<?php
define("LOGO_STRING", "南京第一中文演讲俱乐部，俱乐部编号：3646256，第85区");
define("FOOT_STRING", "版权信息 &copy; 2015 南京第一中文演讲俱乐部版权所有，保留一切追究版权的权利。电子邮件：hroptmc(at)gmail.com");

function showLogo(){
	echo LOGO_STRING;
}

function showFoot(){
	echo FOOT_STRING;
}

function showLogoAndStatus($authorized, $userName, $pageNumber, $isDefaultIndex){
	echo "<div id='fixedMenuContainer'>";
	showBanner();
	echo "<div id='menuLengthContainer'>";
	showMenu($pageNumber, $isDefaultIndex);
	showStatus($authorized, $userName);
	echo "</div>";
	echo "</div>";
}

function showBanner(){
	echo "<div id='logoBar'>";
	showLogo();
	echo "</div>";
}

function showMenu($pageNo, $isDefaultIndex){
	$pages = array("index.php", "rolerules.php", "manageagendas.php", "members.php");

	$showTexts = array("主页", "角色规范", "议程管理", "会员");
	echo "<div id='menuBar'>";
	printSpace(5);
	foreach($pages as $key => $value){
		printMenuItem((($pageNo - 1) != $key) || ((($pageNo - 1) == $key) && (!$isDefaultIndex)), $value, $showTexts[$key]);
	}
	printLinkToBlog();
 	printSpace(3);
//	printLinkToContest();
// 	printLinktoBBS();
	echo "</div>";
}

function showStatus($authorized, $userName){
	echo "<div id='statusBar'>";

	if($authorized){
		mysql_query("SET NAMES UTF8");
		$sqlLink = getMysqlConnection(CONST_MYSQL_HOST, CONST_MYSQL_USER_NAME, CONST_MYSQL_PASSWORD, CONST_DB_NAME_HROPTMC);
		$userObject = SqlUtil::getFetchObjectByGivenField(SqlUtil::USERS_FIELD_NAME_USERNAME, $userName, SqlUtil::DB_NAME_USERS, $sqlLink);
		$memberObject = SqlUtil::getFetchObjectByGivenField(SqlUtil::MEMBERS_FIELD_NAME_CLUBID, $userObject->clubid, SqlUtil::DB_NAME_MEMBERS, $sqlLink);
		echo "欢迎回来，".$memberObject->MemberName;
		printSpace(3);
		echo "<a href='logout.php'>登出</a>";
	} else {
		echo "<a href='login.php'>登陆</a>";
		printSpace(3);
		echo "<a href='register.php'>注册</a>";
	}

	echo "</div>";
}

function showFootInfo(){
	echo "<div id='footBar'>";
	showFoot();
	echo "</div>";
}

function printSpace($number){
	if(isset($number) && ($number > 0)){
		for($spaceIndex = 0; $spaceIndex < $number; $spaceIndex++){
			echo "&nbsp;";
		}
	} else {
		echo "&nbsp";
	}
}

function printBreakLine($number){
	if(isset($number) && ($number > 0)){
		for($spaceIndex = 0; $spaceIndex < $number; $spaceIndex++){
			echo "<br />";
		}
	} else {
		echo "<br />";
	}
}

function printMenuItem($isToLink, $page, $showText){
	if($isToLink){
		echo "<a href='".$page."'>".$showText."</a>";
	} else {
		echo $showText;
	}
	printSpace(3);
}

function printLinkToBlog(){
	echo "<a href='http://www.libredesign.info'><img src='img/blog_character.jpg' height='25' align='center' border='0' alt='Blog'></a>";
}

function printLinkToContest(){
    echo "<a href='speechcontest.php'>比赛（热门）</a>";
}

function printLinktoBBS(){
	echo "<a href='http://bbs.libredesign.info'><img src='img/bbs-icon.gif' height='25' align='center' border='0' alt='Blog'></a>";
}

function printLoginPrompt($fileName, $userNameId, $passwordId, $passwordMD5Id, $loginFormId, $actionValue){
	echo "<div id='centerDiv'>
			<form id='".$loginFormId."' method='post' action='authenticate.php'>
				<h3>请登录：</h3>
				<input type='hidden' name='actionId' value='".$actionValue."' />
				<input type='hidden' name='backToFileName' value='".$fileName."' />
				<input type='hidden' name='".$passwordMD5Id."' id='".$passwordMD5Id."'/>
				<p>
					<label>用户名：</label><br / >
					<input name='".$userNameId."' id='".$userNameId."' type='text' size='34' />
				</p>
				<p>
					<label>密码：</label><br />
					<input name='".$passwordId."' id='".$passwordId."' type='password' size='34' />
				</p>
				<br />
				<a href='index.php'>主页</a>&nbsp;&nbsp; | &nbsp;&nbsp;
				<a href='retrivepass.php'>忘记密码？</a>&nbsp;&nbsp;&nbsp;
				<input type='submit' id='submitForm' value='登录'></td>
				</form>
				</div>";
}

function printRegisterPrompt($fileName, $userNameId, $passwordId, $passwordMD5Id, $regFormId, $actionValue){
	echo "<div id='centerDiv'>
			<form id='".$regFormId."' method='post' action='authenticate.php'>
				<h3>注册信息：</h3>
				<input type='hidden' name='actionId' value='".$actionValue."' />
				<input type='hidden' name='backToFileName' value='".$fileName."' />
				<input type='hidden' name='".$passwordMD5Id."' id='".$passwordMD5Id."'/>
				<table>
					<tr><td>会员编号：</td><td><input name='clubId' id='clubId' type='text' size='30' /></td></tr>
					<tr><td>用户名：</td><td><input name='".$userNameId."' id='".$userNameId."' type='text' size='30' /></td></tr>
					<tr><td colspan='2'>&nbsp;</td></tr>
					<tr><td>密码：</td><td><input name='".$passwordId."' id='".$passwordId."' type='password' size='30' /></td></tr>
					<tr><td>再输入一次密码：</td><td><input name='".$passwordId."2' id='".$passwordId."2' type='password' size='30' /></td></tr>
				</table>
				<br />
				<a href='index.php'>主页</a>&nbsp;&nbsp; | &nbsp;&nbsp;
				<input type='submit' id='submitForm' value='注册'></td>
			</form>
		</div>";
}

?>
