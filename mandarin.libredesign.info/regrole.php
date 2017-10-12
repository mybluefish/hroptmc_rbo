<?php
	session_start();

	require_once("config/admincfg.php");
	require_once("config/autoload.php");
	require_once 'common/servicecommon.php';

	$mysqlUtil = MysqlUtil::getInstance();

	// Retrive member date from databases, store it in $memberObject
	$memberObject;

	if(isset($_SESSION["USERNAME"]) && isset($_SESSION["CLUBID"])){
		$memberObject = Member::getMemberInstanceFromDB($_SESSION["CLUBID"]);
	}

	$meetingDate = $_POST["meetingdate"];
	$keyOfRoleName = $_POST["rolename"];

	$inputKeySelection = array("会员编号：", "姓名：", "不是我们会员？");

	$roleNameObject = RoleNames::getInstance();
	$roleName = $roleNameObject->getRoleNameByRoleKey($keyOfRoleName);

	$levelIdentify = "cclevelindex";
	$projectIdentify = "ccproject";

	echo "<form name='submitRole' id='submitRole'>";

		echo "<table width='500'>";

			//  This is line of close action at the right top of the prompt
			echo "<tr >
					<td style='border-bottom-width:1px;border-bottom-style:dotted;' colspan='2' align='right'><a href='javascript:void(0)' onClick='closeRegPrompt()'>关闭</a></td>
		  		  </tr>";

			// This is the line of meeting date
			echo "<tr><td style='width:150px'>会议日期</td><td><label for='submitRole' id='meetingdate' value='".$meetingDate."'>".$meetingDate."</label></td></tr>";


			if($keyOfRoleName == RegularMeeting::CONST_SPECIAL_FORMAT_THEME){
				//#################################################################################
				//# Show the input of THEME
				//#################################################################################
				echo "<tr>
						<td colspan='2'><input type='hidden' for='submitRole' id='rolename' value='".$roleName."' /></td>
			  		  </tr>";

				echo "<tr>
						<td>会议主题：</td>
						<td><input type='text' style='width:250px' size='150' id='inputvalue' />&nbsp;<font color='red'>*</font></td>
			  	 	  </tr>";
			} else {
				//#################################################################################
				//# Show the name input textfield or selection for member names or ID
				//#################################################################################
				echo "<tr>
						<td>角色：</td>
						<td><label for='submitRole' id='rolename'>".$roleName."</label></td>
					  </tr>";
				if(isset($memberObject)){
					$memberMgmt = MemberManager::getInstance($_SESSION["CLUBID"], "memberContainer");
// 					echo "<tr>
// 							<td><input type='hidden' id='inputkey' value='1'>Your Name: </td>
// 							<td><input type='hidden' id='inputvalue' value='".$memberObject->MemberName."'>".$memberObject->MemberName."</td>
// 				 		  </tr>";
					echo "<tr>
							<td>
								<input type='hidden' id='inputkey' value='0'>
								姓名：
							</td>";
					if(RoleNames::isRoleTypeNoCCAndNoCL($keyOfRoleName)){
						echo "<td>".$memberMgmt->getValidMemberSelectionList($_SESSION["CLUBID"], $keyOfRoleName, "inputvalue", null)."</td>";
					} else {
						echo "<td>".$memberMgmt->getValidMemberSelectionList($_SESSION["CLUBID"], $keyOfRoleName, "inputvalue", "memberNameListChange")."</td>";
					}
					echo "</tr>";
				} else {
					echo "<tr>
							<td  style='vertical-align:text-top;padding-top: 10px'>
								<select id='inputkey' onChange=inputKeyChange('inputkey','inputvalue','example');>";
									for($inputKeyIndex = 0; $inputKeyIndex < count($inputKeySelection); $inputKeyIndex++){
										echo "<option value='".$inputKeyIndex."'>".$inputKeySelection[$inputKeyIndex]."</option>";
									}
					  	  echo "</select>
					  		</td>
					  		<td id='inputName'>
					  	  		<input style='width:250px' type='text' size='150' id='inputvalue' />&nbsp;<font color='red'>*</font>
					  	  	</td>
					 	  </tr>";
// 					echo "<tr>
// 							<td>&nbsp;</td>
// 							<td id='inputContactInfo'></td></tr>";
					echo "<tr>
							<td>&nbsp;</td>
							<td id='inputOther'><font color='red'><label id='example'>例如：4 (4是姜坤)</label></font></td>
					  	  </tr>";
				}
			}

	if(RoleNames::isRoleTypeCC($keyOfRoleName)){
		$levelIdentify = "cclevelindex";
		$projectIdentify = "ccproject";
		$ccSelected = 1;
		$projectSelected = 1;

		if(isset($memberObject)){
			$ccSelected = $memberObject->getNextLevelCCByIndex();
			$projectSelected = $memberObject->getNextProjectCC();
		}

		echo "<tr>
				<td>CC项目级别：</td>
				<td>
					<select id='cclevelindex' onChange='changeccProject();' style='width:58px'>";
						for($ccIndex = 1; $ccIndex < count(Member::$CC); $ccIndex++){
							echo "<option value='".$ccIndex."' ".(($ccIndex == $ccSelected)?"selected":"")." >".Member::$CC[$ccIndex]."</option>";
						}
			  echo "</select>
			  	</td>
			  </tr>";

		echo "<tr>
				<td>项目：</td>
				<td><select id='ccproject' style='width:58px'>";
					for($index = 1; $index <= 10; $index++){
						echo "<option value='".$index."' ".(($index == $projectSelected)?"selected":"")." >P".$index."</option>";
					}
			echo "</select>
				</td>
			 </tr>";
	} elseif (RoleNames::isRoleTypeCL($keyOfRoleName)){
		$levelIdentify = "cllevelindex";
		$projectIdentify = "clproject";
		if(RoleNames::isTme($keyOfRoleName)){
			// To check that whether current role is taken by someone else
			$checkSql = "SELECT ".RegularMeeting::CONST_SPECIAL_FORMAT_THEME." FROM ".RegularMeeting::CONST_TABLE_NAME." WHERE ".RegularMeeting::CONST_FIELD_DATE."='".$meetingDate."';";

			$sqlResult = $mysqlUtil->queryGetResult($checkSql);

			$sqlObject;

			if($mysqlUtil->getQueryRowNum($sqlResult)){
				$sqlObject = $mysqlUtil->getQueryResultObject($sqlResult);
			}

			if(isset($sqlObject) && ($sqlObject->theme != "") && ($sqlObject->theme != null)){
				echo "<tr><td>会议主题：</td><td>".$sqlObject->theme."</td></tr>";
			} else {
				echo "<tr><td>会议主题：</td><td><input type='text' style='width:250px' size='150' id='themevalue' /></td></tr>";
			}

// 				echo "<tr><td>Theme of this meeting: </td><td><input type='text' style='width:250px' size='150' id='themevalue' ".(isset($sqlObject)?("value='".$sqlObject->theme."'"):"")." /></td></tr>";
		}

		$clSelected = 1;
		$projectSelected = 1;

		if(isset($memberObject)){
			$ccSelected = $memberObject->getNextLevelCLByIndex();
			$projectSelected = $memberObject->getNextProjectCL();
		}

		echo "<tr>
				<td>CL项目级别：</td>
				<td>
					<select id='cllevelindex' onChange='changeclProject();' style='width:58px'>";
						for($clIndex = 1; $clIndex < count(Member::$CL); $clIndex++){
							echo "<option value='".$clIndex."' ".(($clIndex == $clSelected)?"selected":"")." >".Member::$CL[$clIndex]."</option>";
						}
				echo "</select>
				</td>
			 </tr>";

		echo "<tr>
				<td>项目</td>
				<td><select id='clproject' style='width:58px'>";
					for($index = 1; $index <= 10; $index++){
						echo "<option value='".$index."' ".(($index == $projectSelected)?"selected":"")." >P".$index."</option>";
					}
			  echo "</select>
			  	</td>
			 </tr>";
	}

	echo "<tr>
			<td colspan='2' align='right'>";
			if((!isset($_SESSION["USERNAME"])) || (isset($_SESSION["USERNAME"]) && (RoleNames::isTheme($keyOfRoleName))))
				echo "<input type='reset' name='cancel' id='cancel' value='重置'/>&nbsp;";
			elseif (isset($_SESSION["USERNAME"]) && (RoleNames::isRoleTypeCCOrCL($keyOfRoleName))){
				echo "<input type='button' name='cancel' id='roleRegReset' value='重置' onClick=\"resetRegForm('submitRole', 'inputkey', 'inputvalue')\"  />&nbsp;";
			}

	echo "<input type='button'
		name='submit' value='提交' onClick=\"submitReg('".$meetingDate."','".$keyOfRoleName."','inputkey','inputvalue','".$levelIdentify."','".$projectIdentify."')\" /></td></tr>";

	echo "</form>";
?>
<script type="text/javascript">
$(function(){
    $('#cancel').unbind('click').bind('click',function(event){
        event.preventDefault();
        if($("#rolename").val() != "Theme"){
            $("#inputkey").val(0);
            inputKeyChange('inputkey','inputvalue','example');
        }
        $('form#submitRole')[0].reset();
    });
});
</script>
