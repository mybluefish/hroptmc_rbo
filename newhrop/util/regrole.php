<?php

require_once("../config/autoload.php");

$meetingDate = $_POST["meetingdate"];
$keyOfRoleName = $_POST["rolename"];

$roleNamesObj = new RoleNames();

$inputKeySelection = array("Your Club ID: ", "Your Name: ", "Not our member?");

echo '<form name="submitRole" id="submitRole">';

echo '<table>';

echo '<tr><td colspan="2">Close</td></tr>';
echo '<tr>
		<td>MeetingDate: </td>
		<td><label for="submitRole" id="meetingdate" value="'.$meetingDate.'">'.$meetingDate.'</label></td>
	  </tr>';

if(EducationalUtil::isRoleTypeTheme($keyOfRoleName, $roleNamesObj)){
	//#################################################################################
	//# Show the input of THEME
	//#################################################################################
	echo "<tr>
		 	<td colspan='2'><input type='hidden' for='submitRole' id='rolename' value='".$keyOfRoleName."' /></td>
		  </tr>";
		
	echo "<tr>
			<td>Theme of this meeting: </td>
			<td><input type='text' style='width:250px' size='150' id='inputvalue' />&nbsp;<font color='red'>*</font></td>
		  </tr>";
} else {
	//#################################################################################
	//# Show the name input textfield or selection for member names or ID
	//#################################################################################
	echo "<tr>
			<td>Rolename: </td>
			<td><label for='submitRole' id='rolename'>".$keyOfRoleName."</label></td>
		  </tr>";
		 if(isset($memberObject)){
			$memberMgmt = MemberManager::getInstance($_SESSION["CLUBID"], "memberContainer");

					echo "<tr>
							<td>
								<input type='hidden' id='inputkey' value='0'>
								Your Name: 
							</td>";
					if(RoleNames::isRoleTypeNoCCAndNoCL($keyOfRoleName)){
						echo "<td>".$memberMgmt->getValidMemberSelectionList($_SESSION["CLUBID"], $keyOfRoleName, "inputvalue", null)."</td>";
					} else {
						echo "<td>".$memberMgmt->getValidMemberSelectionList($_SESSION["CLUBID"], $keyOfRoleName, "inputvalue", "memberNameListChange")."</td>";
					}
					echo "</tr>";
		} else {
					echo "<tr>
							<td>	
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
					echo "<tr>
							<td>&nbsp;</td>
							<td id='inputOther'><font color='red'><label id='example'>Example: 4 (4 is Jerry Jiang)</label></font></td>
					  	  </tr>";
				}
			}
			
echo '</table>';

echo '</form>';
?>