<?php

/**
 *  Class for regular meeting
 */
class RegularMeeting{
	// Meeint Date should be of type Class MeetingDate
	private $date;

	private $allRoles = array();

	private $agendaRoleNames;
	
	private $allNames = array("date", "allRoles", "agendaRoleNames", "editAble", "isclosed");
	
	private $editAble;
	
	private $isclosed;
	
	const CONST_ROLE_INDEX_PRESIDENT = 0;
	const CONST_ROLE_INDEX_EMPTY = 1;
	
	const CONST_TABLE_NAME = "meetingagendaroles";
	
	const CONST_FIELD_DATE = "date";
	const CONST_FIELD_ISCLOSED = "isclosed";
	
	const CONST_SPECIAL_FORMAT_THEME = "theme";
	const CONST_SPECIAL_FORMAT_TME = "tme";
	const CONST_SPECIAL_FORMAT_SPEAKER = "speaker";
		
	const CONST_VALUE_MEETING_CLOSED = 1;

	const SPILT_DATE_TIME = "2014-3-6";
	
	public function __construct(RoleNames $roleNames, MeetingDate $meetingDate, $isEditAble){
		$this->agendaRoleNames = $roleNames->nameArray;
		$this->date = $meetingDate;
		$this->editAble = $isEditAble;
		
		$mysqlUtil = MysqlUtil::getInstance();
		$sqlSentence = "SELECT * FROM ".RegularMeeting::CONST_TABLE_NAME." WHERE ".RegularMeeting::CONST_FIELD_DATE."='".$meetingDate->toString()."';";
		$sqlQuery = $mysqlUtil->queryGetResult($sqlSentence);
		
		if($this->date->isNormalMeeting()){
		
		  // if no records for current meeting is found, create a new record for it at the first time
		  if($mysqlUtil->getQueryRowNum($sqlQuery) == 0){
		      // Insert a new record for a totally new meeting agenda
	          if(!$mysqlUtil->queryGetResult($this->getCreateMeetingAgendaSQL($this->date))){
				die("ERROR: FAIL TO CREATE A NEW RECORD FOR MEETING AGENDA!!! At line ".__LINE__." in file ".__FILE__."<br>");
			  }
			
			  $this->insertPresidentToAgendaTable() or die("Fail to update president to meeting agenda!!!");
			
			  $sqlQuery = $mysqlUtil->queryGetResult($sqlSentence)
				or die("ERROR: FAIL TO SELECT THE NEW CREATED RECORD FOR MEETING AGENDA!!! At line ".__LINE__." in file ".__FILE__."<br>");
			
			  if($mysqlUtil->getQueryRowNum($sqlQuery) == 0){
				die("DATE STILL CAN NOT BE FOUND IN DATEBASE, PLEASE CHECK THE STATUS OF DATEBASE!!!<br>");
			  } 
		  }
		
		  $queryResult = $mysqlUtil->getQueryResultArray($sqlQuery);
		
		  if(!$this->isPresidentInserted($queryResult[RoleNames::CONST_VALUE_PRESIDENT])){
		    $this->insertPresidentToAgendaTable();
		    $sqlQuery = $mysqlUtil->queryGetResult($sqlSentence);
		    $queryResult = $mysqlUtil->getQueryResultArray($sqlQuery);
		  }
		
		  // Initialize the member variables with data feteched from datebase
		  foreach ($roleNames->nameArray as $key => $value) {
			if(array_key_exists($key, $queryResult)){					
				$this->allRoles[$key] = $queryResult[$key];
			}
		  }
		
		  if(array_key_exists(RegularMeeting::CONST_FIELD_ISCLOSED, $queryResult)){
			$this->{RegularMeeting::CONST_FIELD_ISCLOSED} = $queryResult[RegularMeeting::CONST_FIELD_ISCLOSED];
		  } else {
			$this->{RegularMeeting::CONST_FIELD_ISCLOSED} = RegularMeeting::CONST_VALUE_MEETING_CLOSED;
		  }
		} else {
		    // If it is not normal meeting, delete the regular meeting records if already created before
		    if($mysqlUtil->getQueryRowNum($sqlQuery) != 0){
		        $sqlSentence = "DELETE FROM ".RegularMeeting::CONST_TABLE_NAME." WHERE ".RegularMeeting::CONST_FIELD_DATE."='".$meetingDate->toString()."';";
		        
		        if(!$mysqlUtil->queryGetResult($sqlSentence)){
		            die("ERROR: FAIL TO DELETE THE MEETING RECORD FOR NONORMAL MEETING!! At line ".__LINE__." in file ".__FILE__."<br>");
		        }
		    }
		}
	}

	public function __get($propName){
		if(in_array($propName, $this->allNames)){
			return $this->$propName;
		}
	}

	public function drawToTable($tableClass, $trClass, $tdClass, $tableId, $trId, $tdId){
		echo "<table".(isset($tableClass)?" class='".$tableClass."'":"").
		(isset($tableId)?" id='".$tableId."'":"").">";
		
		//print the line of date
		echo "<tr".(isset($trClass)?" class='".$trClass."'":"").
		(isset($trId)?" id='".$trId."'":"")."><td".
		(isset($tdClass)?" class='".$tdClass."'":"").
		(isset($tdId)?" id='".$tdId."'":"").">".$this->date->toString()."</td></tr>";

		//print roles
		if($this->date->isNormalMeeting()){
			foreach ($this->agendaRoleNames as $key => $value) {
				if(array_key_exists($key, $this->allRoles)){
					echo "<tr".(isset($trClass)?" class='".$trClass."'":"").
					(isset($trId)?" id='".$trId."'":"")."><td".
					(isset($tdClass)?" class='".$tdClass."'":"").
					(isset($tdId)?" id='".$tdId."'":"").">".$this->printNames($key, $this->allRoles[$key])."</td></tr>";
				}
			}
		} elseif($this->date->ifIsSpecial()){
			echo "<tr".(isset($trClass)?" class='".$trClass."'":"").
			(isset($trId)?" id='".$trId."'":"")."><td".
			(isset($tdClass)?" class='".$tdClass."'":"").
			(isset($tdId)?" id='".$tdId."'":"").">".$this->printNames(RegularMeeting::CONST_ROLE_INDEX_PRESIDENT, $this->allRoles[RegularMeeting::CONST_ROLE_INDEX_PRESIDENT])."</td></tr>";
			
			echo "<tr".(isset($trClass)?" class='".$trClass."'":"").
			(isset($trId)?" id='".$trId."'":"")."><td".
			(isset($tdClass)?" class='".$tdClass."'":"").
			(isset($tdId)?" id='".$tdId."'":"")." rowspan='".(count($this->agendaRoleNames) - 1)."'>".$this->date->getExceptionalReason()."</td></tr>";
		}


		echo "</table>";
	}
	
	public function getOnlyMeetingDate($meetingDateClass, $noOfAgendasInOneScreen, $idIndex){
		$roleNameHeaderID = "roleHeader";
		
		$meetingDateReturnString = "<td id='".$roleNameHeaderID.$idIndex."' ".(isset($meetingDateClass)?"class='".$meetingDateClass."'":"")." onMouseOver=\"changeColorMouseOverOrOut('".$roleNameHeaderID."', ".$noOfAgendasInOneScreen.", true)\" onMouseOut=\"changeColorMouseOverOrOut('".$roleNameHeaderID."', ".$noOfAgendasInOneScreen.", false)\">".$this->date->toString()."&nbsp;";

		if($this->isMeetingClosed()){
			$meetingDateReturnString .= $this->echoClosed();
		} else if($this->date->isInThePast()){
			$meetingDateReturnString .= $this->echoPassed();
		} else {
			$meetingDateReturnString .= $this->echoOpen();
		}
		
		return $meetingDateReturnString;
	}

	public function getSingleRole($roleClass, $roleIndex, $noOfAgendasInOneScreen, $idIndex){
		if($this->date->isNormalMeeting()){
			
			$keys = array_keys($this->agendaRoleNames);
			return "<td id='".$keys[$roleIndex].$idIndex."' ".(isset($roleClass)?"class='".$roleClass."'":"").
				" onMouseOver=\"changeColorMouseOverOrOut('".$keys[$roleIndex]."', ".$noOfAgendasInOneScreen.
				", true)\" onMouseOut=\"changeColorMouseOverOrOut('".$keys[$roleIndex]."', ".$noOfAgendasInOneScreen.
				", false)\">".$this->printNames($keys[$roleIndex], $this->allRoles[$keys[$roleIndex]])."</td>";
			
		} else {
		    if($roleIndex == RegularMeeting::CONST_ROLE_INDEX_PRESIDENT){
		      $keys = array_keys($this->agendaRoleNames);
		        
		      return "<td id='".$keys[$roleIndex].$idIndex."' ".(isset($roleClass)?"class='".$roleClass."'":"").
		        " onMouseOver=\"changeColorMouseOverOrOut('".$keys[$roleIndex]."', ".$noOfAgendasInOneScreen.
		        ", true)\" onMouseOut=\"changeColorMouseOverOrOut('".$keys[$roleIndex]."', ".$noOfAgendasInOneScreen.
		        ", false)\">".$this->printNames($keys[$roleIndex], $this->getInsertedPresidentData($this->date->toString()))."</td>";
		      
		    } elseif ($roleIndex == RegularMeeting::CONST_ROLE_INDEX_EMPTY){
		        
			 return "<td rowspan='".(count($this->agendaRoleNames) - 1)."' id='meetingNotRegular".$this->date->toString().
			         "' class='meetingNotRegular' onMouseOver=\"changeColorMeetingNotRegular('meetingNotRegular".$this->date->toString().
			         "', true)\" onMouseOut=\"changeColorMeetingNotRegular('meetingNotRegular".$this->date->toString()."', false)\">".
			         $this->date->getExceptionalReason()."</td>";
			 
		    }
		}
	}
	
	private function printNames($roleName, $regName){
		
		$namesToBePrinted = "";
		
		if($regName == "" || $regName == null || $regName == "NULL"){
			if((!RoleNames::isPresident($roleName)) && (!$this->date->isInThePast()))
				$namesToBePrinted = "<a href='javascript:void(0)' onClick=\"regRole('".$this->date->toString()."','".$roleName."',event)\">Reserve</a>";
			else
				$namesToBePrinted = "-";
		} else {
			$agendaFieldContentObject = FieldContentInAgenda::getFieldContentInAgendaFromContent($this->date->toString(), $roleName, $regName);
			
			$namesToBePrinted = $agendaFieldContentObject->getPrintedFormat();
			
			if(!(RoleNames::isPresident($roleName) || $this->date->isInThePast() || !$this->editAble)){
				$namesToBePrinted = $namesToBePrinted."&nbsp;(<a href='javascript:void(0)' onClick=\"delRole('".$this->date->toString()."','".$roleName."')\">X</a>)";
			}
		}
		
		return $namesToBePrinted;
	}

	/**
	 * To create a new record for a regular meeting 
	 * @param MeetingDate $meetingDate
	 * @return string
	 */
	private function getCreateMeetingAgendaSQL(MeetingDate $meetingDate){
		return "INSERT INTO  `".SqlUtil::DB_NAME_MEETING_AGENDA_ROLES."` (`".RegularMeeting::CONST_FIELD_DATE."`) VALUES ('".$meetingDate->toString()."');";
	}
	
	private function insertPresidentToAgendaTable(){
		$presidentInsertData = $this->getInsertedPresidentData($this->date->toString());
		
		$sqlSentence = "UPDATE ".RegularMeeting::CONST_TABLE_NAME." SET ".RoleNames::CONST_VALUE_PRESIDENT."='".$presidentInsertData."' WHERE date='".$this->date->toString()."'";
		
		$mysqlUtil = MysqlUtil::getInstance();
		
		return $mysqlUtil->queryGetResult($sqlSentence);
	}
	
	private function getInsertedPresidentData($dateString){
	    $presidentInsertData = "NULL";
	    
	    $presidentId = Officer::getOfficerId($dateString, Officer::CONST_FIELD_PRESIDENT);
	    
	    if($presidentId != Member::CONST_NON_MEMBER_ID){
	        $presidentObject = Member::getMemberInstanceFromDB($presidentId);
	        	
	        $tempFieldContentObject = FieldContentInAgenda::getFieldContentInAgendaFromInfoProvided($this->date->toString(), RoleNames::CONST_VALUE_PRESIDENT);
	        	
	        $tempFieldContentObject->setMember(true);
	        $tempFieldContentObject->setId($presidentObject->ClubID);
	        $tempFieldContentObject->setMemberName($presidentObject->MemberName);
	        $tempFieldContentObject->setMemberLevelInCurrent($presidentObject->getMemberLevel());
	        $tempFieldContentObject->setEmpty(false);
	        $presidentInsertData = $tempFieldContentObject->getDatabaseFormat();
	    }
	    
	    return $presidentInsertData;
	}
	
	private function isPresidentInserted($presidentStr){
	    return (isset($presidentStr) && ($presidentStr != "NULL"));
	}
	
	public function setEditAble($setValue){
		$this->editAble = $setValue;
	}
	
	public function isEditAble(){
		return $this->editAble;
	}
	
	public function isMeetingClosed(){
		return ($this->isclosed == RegularMeeting::CONST_VALUE_MEETING_CLOSED);
	}
	
	public function echoClosed(){
		return "(<span id='meetingclosed'>Closed</span>)";
	}
	
	public function echoPassed(){
		if(isset($_SESSION["USERNAME"])){
			return  "(<span id='meetingpassed'><a id='editMeetingLink' href='javascript:void(0)' onClick='meetingDateChangeOfAgendaManage(\"".$this->date->toString()."_close\")'>Passed</a></span>)				
					 <form id='".$this->date->toString()."_close' action='manageagendas.php' method='post'>
						<input type='hidden' name='".ConstantValue::CONST_FIELD_AGENDA_MANAGE_DATE_SELECT."' value='".$this->date->toString()."' />						
					 </form>";
		} else {
			return  "(<span id='meetingpassed'>Passed</span>)";
		}
	}
	
	public function echoOpen(){
		if(isset($_SESSION["USERNAME"])){
			return  "(<span id='meetingopen'><a id='editMeetingLink' href='javascript:void(0)' onClick='meetingDateChangeOfAgendaManage(\"".$this->date->toString()."_close\")'>Open</a></span>)
					 <form id='".$this->date->toString()."_close' action='manageagendas.php' method='post'>
						<input type='hidden' name='".ConstantValue::CONST_FIELD_AGENDA_MANAGE_DATE_SELECT."' value='".$this->date->toString()."' />						
					 </form>";
		} else {
			return  "(<span id='meetingopen'>Open</span>)";
		}
	}
	
	public function isGuestRegistered(Guest $guest){
		$isGuestRegistered = false;
		
		$sqlSentence = "SELECT * FROM ".RegularMeeting::CONST_TABLE_NAME." WHERE ".RegularMeeting::CONST_FIELD_DATE."='".$this->date->toString()."';";
		
		$mysqlUtil = MysqlUtil::getInstance();
		
		$sqlResult = $mysqlUtil->queryGetResult($sqlSentence);
		
		if($mysqlUtil->getQueryRowNum($sqlResult) != 0){
			$sqlRestultObject = $mysqlUtil->getQueryResultObject($sqlResult);
			foreach (array_keys($this->agendaRoleNames) as $value){
				$eachFieldInAgenda = $sqlRestultObject->{$value};
				if(($eachFieldInAgenda != "") && ($eachFieldInAgenda != null)){
					$tempFieldContentObject = FieldContentInAgenda::getFieldContentInAgendaFromContent($this->date->toString(), $value, $eachFieldInAgenda);
					if((!$tempFieldContentObject->isMember()) && (!$tempFieldContentObject->isInOldFormat()) && $guest->isSameId($tempFieldContentObject->getId())){
						$isGuestRegistered = true;
						break;
					}
				}
			}
		}
		
		return $isGuestRegistered;
		
	}
	
	public static function getMeetingDateList(){
		$sqlSetence = "SELECT `".RegularMeeting::CONST_FIELD_DATE."` FROM `".RegularMeeting::CONST_TABLE_NAME."`;";
		
		$mysqlUtil = MysqlUtil::getInstance();
		
		$queryResult = $mysqlUtil->queryGetResult($sqlSetence);
		
		$meetingDateArray = array();
		
		while($singleMeetingDate = $mysqlUtil->getQueryResultArray($queryResult)){
			array_push($meetingDateArray, $singleMeetingDate[RegularMeeting::CONST_FIELD_DATE]);
		}
		
		usort($meetingDateArray, "dateCompare");
		
		return $meetingDateArray;
	}
	
	/**
	 * Draw agenda table for update and close
	 */
	public function drawUpdateAndCloseTable($updateFormName, $updateAction, $closeOpenAction){
		echo "\n\n";
		
		echo "<table id='agendaTableToUpdate'>\n";
		
		// Meeting Date
		echo "<tr>
				<td>Date:</td>
				<td><input type='hidden' name='date' value='".$this->date->toString()."'/>".$this->date->toString()."<td>
			  </tr>\n";
			
		// Meeting Status
		echo "<tr>
				<td>Meeting Status:</td>
				<td>".($this->isMeetingClosed()?"Closed":"Open")."</td>
			  </tr>\n";
		
		$memberMgmt = MemberManager::getInstance($_SESSION["CLUBID"], "memberContainer");
		$memberRecords = $memberMgmt->getAllMembers();
		
		// All information that can be updated
		foreach($this->agendaRoleNames as $roleKey => $roleName){
			$fieldContentFromDatabase = FieldContentInAgenda::getFieldContentInAgendaFromContent($this->date->toString(), $roleKey, $this->allRoles[$roleKey]);
			echo "<tr>
					<td style='width:220px'>".$roleName.":</td>
					<td id='".$roleKey."'>";
			
			if(RoleNames::isPresident($roleKey)){
				// President can not be changed, at least now
				echo "<label>".$fieldContentFromDatabase->getPrintedFormat()."</label>";
	
			} elseif(RoleNames::isTheme($roleKey)) {
				// THEME is a text field, it can be edited
				echo "<input type='textfield' style='width:485px' id='".ConstantValue::CONST_FIELD_THEME_ID."' name='".ConstantValue::CONST_FIELD_THEME_NAME.
					"' value='".($fieldContentFromDatabase->getPrintedFormat())."' ".($this->isMeetingClosed() ? "disabled" : "")."/>";
			} else {
				echo "<span id='".ConstantValue::CONST_FIELD_CHECKBOX_NON_MEMBER_SPAN_PREFIX.$roleKey."' name='".ConstantValue::CONST_FIELD_CHECKBOX_NON_MEMBER_SPAN_PREFIX.ConstantValue::CONST_FIELD_NAME_MIDFIX.$roleKey."'>
						<input type='checkbox' id='".ConstantValue::CONST_FIELD_CHECKBOX_CHOOSE_NON_MEMBER_PREFIX.$roleKey."' 
								name='".ConstantValue::CONST_FIELD_CHECKBOX_CHOOSE_NON_MEMBER_PREFIX.ConstantValue::CONST_FIELD_NAME_MIDFIX.$roleKey.
								"' onChange='notAMemberCheckOn(\"CNM_".$roleKey."\")' ".($this->isMeetingClosed() ? "disabled" : "").
								"  ".(($fieldContentFromDatabase->isMember() || $fieldContentFromDatabase->isEmpty())? "" : "checked")."/>N/A Member?
					  </span> 
					  &nbsp;&nbsp;&nbsp;";
				
				$tempValidMember = null;
				$tempFieldContentInAgendaObject = null;
				
				//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				// Set member list, and make the booked role to be chosen
				$roleToBeChosenId = ConstantValue::CONST_FIELD_ROLE_NAME_TO_BE_CHOOSEN_PREFIX.$roleKey;
				$levelId = ConstantValue::CONST_FIELD_LEVEL_PREFIX.$roleKey;
				$projectId = ConstantValue::CONST_FIELD_PROJECT_LEVEL_PREFIX.$roleKey;
				$spanMemberInfoId = ConstantValue::CONST_FIELD_MEMBER_INFO_SPAN_PREFIX.$roleKey;
				$guestInfoId = ConstantValue::CONST_FIELD_GUEST_INFO_PREFIX.$roleKey;
				
				$roleToBeChosenName = ConstantValue::CONST_FIELD_ROLE_NAME_TO_BE_CHOOSEN_PREFIX.ConstantValue::CONST_FIELD_NAME_MIDFIX.$roleKey;
				$levelName = ConstantValue::CONST_FIELD_LEVEL_PREFIX.ConstantValue::CONST_FIELD_NAME_MIDFIX.$roleKey;
				$projectName = ConstantValue::CONST_FIELD_PROJECT_LEVEL_PREFIX.ConstantValue::CONST_FIELD_NAME_MIDFIX.$roleKey;
				$spanMemberInfoName = ConstantValue::CONST_FIELD_MEMBER_INFO_SPAN_PREFIX.ConstantValue::CONST_FIELD_NAME_MIDFIX.$roleKey;
				$guestInfoName = ConstantValue::CONST_FIELD_GUEST_INFO_PREFIX.ConstantValue::CONST_FIELD_NAME_MIDFIX.$roleKey;
				
				echo "<span id='".$spanMemberInfoId."' name='".$spanMemberInfoName."'>";
				
				if($fieldContentFromDatabase->isMember() || $fieldContentFromDatabase->isEmpty()){
				echo	"<select style='width:230px' id='".$roleToBeChosenId."' name='".$roleToBeChosenName."' "
						.(RoleNames::isRoleTypeCCOrCL($roleKey)?"onChange='memberNameChangeOnManageAgendaPage(\"".$roleKey."\", \"".$roleToBeChosenId."\", \"".$levelId."\", \"".$projectId."\")'":"")." ".($this->isMeetingClosed() ? "disabled" : "").">";
					
					echo "<option value='0'>-</option>";
							
					foreach($memberRecords as $value){
// 						$tempFieldContentObject = FieldContentInAgenda::getFieldContentInAgendaFromContent($this->date->toString(), $roleKey, $this->allRoles[$roleKey]);
						$tempValueToBeCompared = FieldContentInAgenda::isContentInOldFormat($roleKey, $this->allRoles[$roleKey]) ? $value->MemberName : $value->ClubID;
						if($fieldContentFromDatabase->isSameMemberInAgenda($tempValueToBeCompared)){
							$tempValidMember = $value;
							$tempFieldContentInAgendaObject = $fieldContentFromDatabase;
						}
						echo "<option value='".$value->ClubID."' ".(($fieldContentFromDatabase->getMemberName() == $value->MemberName)?"selected":"").">".($value->ClubID.". ".$value->getFormalFormatOfMemberName())."</option>";
					}
				echo "</select>";
				//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
				
				
				//*****************************************************************************************************
				if(RoleNames::isRoleTypeCCOrCL($roleKey)){
					echo "&nbsp;&nbsp;&nbsp;";
					if(RoleNames::isRoleTypeCC($roleKey)){
						echo $this->getLevelAndProjectSelectionString(Member::$CC, $tempValidMember, $tempFieldContentInAgendaObject, $levelId, $levelName, $projectId, $projectName);
					} else {
						echo $this->getLevelAndProjectSelectionString(Member::$CL, $tempValidMember, $tempFieldContentInAgendaObject, $levelId, $levelName, $projectId, $projectName);
					}
				}
				//*****************************************************************************************************
				} else {
					$contentToShow = "";
					if($fieldContentFromDatabase->isInOldFormat()){
						$contentToShow = $fieldContentFromDatabase->getPrintedFormat();
					} else {
						$guestObject = Guest::getGuestInstanceFromDBWithGuestId($fieldContentFromDatabase->getId());
						if($guestObject) {
							$contentToShow = $fieldContentFromDatabase->getPrintedFormat().", ".$guestObject->getPhoneNumber().", ".$guestObject->getEmail();
						} else {
							$contentToShow = $fieldContentFromDatabase->getPrintedFormat();
						}
					}
					echo "<input type='textfield' style='width:356px' id='".$guestInfoId."' name='".$guestInfoName."' value='".
						$contentToShow."' ".($this->isMeetingClosed() ? "disabled" : "").">";
				}
				
				echo "</span>";
			}
						
			echo "</td></tr>";
		}
		
		//Action can be performed
		echo "<tr>
				<td>&nbsp;</td>
				<td style='text-align:center'>
					<input type='button' ".(($this->isMeetingClosed())?"disabled":"")." value='Update' onClick='".$updateAction."(\"".$updateFormName."\",\"". ConstantValue::CONST_FIELD_ROLE_NAME_TO_BE_CHOOSEN_PREFIX."\")'/>
					&nbsp;&nbsp;&nbsp;
					<input type='button' value='".(($this->isMeetingClosed())?"Re-Open":"Close")."' onClick='".$closeOpenAction."()'/>
				</td>
			  </tr>";
		echo "</table>";
	}
	
	private function getLevelAndProjectSelectionString(Array $levelArray, $tempMember, $tempFieldContent, $levelId, $levelName, $projectId, $projectName){
		$levelAndProjectString = "";
		
		//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		$levelAndProjectString .= "<select style='width:58px' id='".$levelId."' name='".$levelName."' ".((($tempMember == null) ||  $this->isMeetingClosed())? "disabled" : "")." onChange='levelChangeListener(\"".$projectId."\")'>";		
		
		if($tempMember == null){
			$levelAndProjectString .= "<option value='TM'>-</option>";
		} 
		for($index = 1; $index < count($levelArray); $index++){
			$levelAndProjectString .= "<option value='".($levelArray[$index])."'";
			if(($tempFieldContent != null) && ($tempFieldContent->getCurrentRoleLevel() == $levelArray[$index])){
				$levelAndProjectString .= " selected";
			}
			$levelAndProjectString .= " >".$levelArray[$index]."</option>";
		}
		
		$levelAndProjectString .= "</select>";
		//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		
		$levelAndProjectString .= "&nbsp;&nbsp;&nbsp;";
		
		//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		$levelAndProjectString .= "<select style='width:50px' id='".$projectId."' name='".$projectName."' ".((($tempMember == null) ||  $this->isMeetingClosed())?"disabled":"")." >";
		if($tempMember == null){
			$levelAndProjectString .= "<option value='0'>-</option>";
		} 
		for($projectIndex = 1; $projectIndex <= Member::MAX_PROJECT; $projectIndex++){
			$levelAndProjectString .= "<option value='".$projectIndex."'";
			if(($tempFieldContent != null) && ($tempFieldContent->getProjectNumber() == $projectIndex)){
				$levelAndProjectString .= " selected";
			}
			$levelAndProjectString .= " >P".$projectIndex."</option>";
		}
			
		$levelAndProjectString .= "</select>";
		//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		
		return $levelAndProjectString;
	}
}
?>