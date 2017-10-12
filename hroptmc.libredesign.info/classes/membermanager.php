<?php

class MemberManager{
	private static $instance;
		
	private $allMemberRecords;
	
	private $names = array("allMemberRecords");
	
	private $memberTableHeadersText = array("Club ID", "Member ID", "Name", "Valid Status","CC", "CL", "CN Name", "Email",
			"Phone No.", "QQ", "Weibo ID", "Birthday", "P_CC", "P_CL");
	
	private $validMembers = array();
	
	private $happyBirthdayThisMonth = array();
	
	private $managerClubID;
	
	private $showAllMembers;
	private $containerId;
	
	private $adminIds = array();
	
	private $superadminIds = array();
	
	const CONST_EDIT_KEY_NA = 0;
	const CONST_EDIT_KEY_ONESELF = 1;
	const CONST_EDIT_KEY_NORMAL_ADMIN = 2;
	const CONST_EDIT_KEY_SUPER_ADMIN = 3;
	
	private $adminKeys = array("adminIds", "superadminIds");
	const CONST_NORMAL_AMIND_KEY = 0;
	const CONST_SUPER_ADMIN_KEY = 1;
	
	public static function getInstance($mClubID, $containerId){
		if(!isset(MemberManager::$instance)){
			MemberManager::$instance = new MemberManager($mClubID, $containerId);
		}
		
		return MemberManager::$instance;
	}
	
	private function __construct($mClubID, $conId){
		global $memberTableKeys;
		
		$this->managerClubID = $mClubID;
		
		$this->containerId = $conId;
		
		$sqlLink = mysql_connect(CONST_MYSQL_HOST, CONST_MYSQL_USER_NAME, CONST_MYSQL_PASSWORD) or die("ERROR: Fail to connect to mysql 
				database. At line ".__LINE__." in file ".__FILE__."<br>");
		
		mysql_select_db(CONST_DB_NAME_HROPTMC, $sqlLink) or die("ERROR: Fail to select the database. At line ".__LINE__." 
				in file ".__FILE__."<br>");
		
		mysql_query("set names utf8");
		
		$sqlQuerySentence = "select * from ".Member::CONST_TABLE_NAME." ORDER BY ".Member::CONST_FIELD_CLUBID." ASC";
		
		$queryResult  = mysql_query($sqlQuerySentence, $sqlLink) or die("ERROR: Fail to execute sql query to retrive 
				information of all members. At line".__LINE__." in File ".__FILE__."<br>");
		
		while($memberRecord = mysql_fetch_array($queryResult)){
			$memberRecordFiltered;
			foreach($memberTableKeys as $key => $value){
				if(array_key_exists($value, $memberRecord)){
					$memberRecordFiltered[$value] = $memberRecord[$value];
				}
			}
			$this->allMemberRecords[$memberRecordFiltered[$memberTableKeys[0]]] = new Member($memberRecordFiltered);
			
			if($this->allMemberRecords[$memberRecordFiltered[$memberTableKeys[0]]]->isValidMember()){
				array_push($this->validMembers, new Member($memberRecordFiltered));
			}
			if($this->allMemberRecords[$memberRecordFiltered[$memberTableKeys[0]]]->Birthday != null){
				$birthdaySlide = explode("-", $this->allMemberRecords[$memberRecordFiltered[$memberTableKeys[0]]]->Birthday);
				if(date("m") == $birthdaySlide[1]){
					array_push($this->happyBirthdayThisMonth, $memberRecordFiltered[$memberTableKeys[0]]);
				}
			}
		}
		
		// Init admins, firstly normal admin, secondly super admin
		foreach($this->adminKeys as $key => $value){
			$sqlQuerySentence = "select ".SqlUtil::USERS_FIELD_NAME_CLUBID." from ".SqlUtil::DB_NAME_USERS." where ".SqlUtil::USERS_FIELD_NAME_ADMINLEVEL."='".MemberManager::CONST_EDIT_KEY_NORMAL_ADMIN."';";
			
			if($key == MemberManager::CONST_SUPER_ADMIN_KEY){
				$sqlQuerySentence = "select ".SqlUtil::USERS_FIELD_NAME_CLUBID." from ".SqlUtil::DB_NAME_USERS." where ".SqlUtil::USERS_FIELD_NAME_ADMINLEVEL.">'".MemberManager::CONST_EDIT_KEY_NORMAL_ADMIN."';";
			}
			
			$queryResult  = mysql_query($sqlQuerySentence, $sqlLink) or die("ERROR: Fail to execute sql query to retrive
				information of all members. At line".__LINE__." in File ".__FILE__."<br>");
			
			while($adminRecords = mysql_fetch_array($queryResult)){
				array_push($this->$value, $adminRecords[SqlUtil::USERS_FIELD_NAME_CLUBID]);
			}
		}
		
		if(in_array($this->managerClubID, $this->adminIds) || in_array($this->managerClubID, $this->superadminIds)){
			$this->showAllMembers = true;
		} else {
			$this->showAllMembers = false;
		}
		
		mysql_close($sqlLink);
	}
	
	public function __get($propName){
		if(in_array($propName, $this->names)){
			return $this->$propName;
		}
	}
	
	public function getSingleMember($id){
		if(array_key_exits($id, $this->allMemberRecords)){
			return $this->allMemberRecords[$id];
		}
	}
	
	public function toString(){
		foreach($this->allMemberRecords as $singleMember){
			$singleMember->toString();
		}
	}
	
	public function happyBirthday($happyBirthdayId){
		echo "<div id='".$happyBirthdayId."'>";
		$this->getHappyBirthdayContent();
		echo "</div>";
	}
	
	public function getHappyBirthdayContent(){
		echo "At present our club has ".count($this->allMemberRecords)." members in all, ".count($this->validMembers)." of them are valid.<br />";
		if(count($this->happyBirthdayThisMonth) != 0){
			echo "This month(".date("M").") ".$this->getNumberOfMembersHaveBirthday()." ".($this->getNumberOfMembersHaveBirthday() > 1 ? "members" : "member").
			" will have birthday! Happy Birthday!!! ( They are: ";
			$firstOne = false;
			foreach($this->happyBirthdayThisMonth as $key => $value){
				if((in_array($this->managerClubID, $this->adminIds) || in_array($this->managerClubID, $this->superadminIds)) && $this->showAllMembers){
					echo ($this->allMemberRecords[$value]->isValidMember()?"":"<font color='red'>").$this->allMemberRecords[$value]->MemberName.
						($this->allMemberRecords[$value]->isValidMember()?"":"</font>").(($key == (count($this->happyBirthdayThisMonth) - 1)) ? ".)" :", ");
				} elseif ($this->allMemberRecords[$value]->isValidMember()){
					if(!$firstOne){
						$firstOne = true;
					} else {
						echo ", ";
					}
					echo $this->allMemberRecords[$value]->MemberName;
				}
			}
				
			if(!((in_array($this->managerClubID, $this->adminIds) || in_array($this->managerClubID, $this->superadminIds)) && $this->showAllMembers)){
				echo ".)";
			}
		} else {
			echo "This month(".date("M").") NO members will have birthday.";
		}
	}
	
	public function getNumberOfMembersHaveBirthday(){
		if((in_array($this->managerClubID, $this->adminIds) || in_array($this->managerClubID, $this->superadminIds)) && $this->showAllMembers){
			return count($this->happyBirthdayThisMonth);
		} else {
			$numOfMembersHaveBirthday = 0;
			foreach($this->happyBirthdayThisMonth as $value){
				if($this->allMemberRecords[$value]->isValidMember()){
					$numOfMembersHaveBirthday++;
				}
			}
			return $numOfMembersHaveBirthday;
		}
	}
	
	public function printActions(){
		echo "<div id='memberMgmtAction'>";
		echo "<table id='memberMgmtTable' class='memberMgmtTableClass'>";
		
		echo "<tr><td>";
		echo $this->showAllMembers ? "Show All Members" : "<a href='javascript:void(0)' onClick='showMembers(\"".$this->containerId."\", \"".$this->managerClubID."\", true)'>Show All Members</a>";
		echo "&nbsp; &nbsp; &nbsp;";
		echo $this->showAllMembers ? "<a href='javascript:void(0)' onClick='showMembers(\"".$this->containerId."\", \"".$this->managerClubID."\", false)'>Show Valid Members</a>" : "Show Valid Members";		
		echo in_array($this->managerClubID, $this->superadminIds) ? "</td><td id='addMemberAction'><a href='javascript:void(0)' onClick='addANewMember()'> Add New Members</td></tr>" : "</td></tr>";
		echo "</table>";
		echo "</div>";
	}
	
	public function drawToPage($tableId, $headerTrId, $memberTrClass, $inValidStatusClass, $happyBirthdayId){
		$this->happyBirthday($happyBirthdayId);
		if(in_array($this->managerClubID, $this->adminIds) || in_array($this->managerClubID, $this->superadminIds)){
			$this->printActions();
		}
// 		echo "<br />";
		echo "<table ".(isset($tableId)?"id='".$tableId."'":"").">";
		
		echo "<tr ".(isset($headerTrId)?"id='".$headerTrId."'":"").">";
		foreach($this->memberTableHeadersText as $header){
			echo "<td>".$header."</td>";
		}
		echo "<td id='editMember'>Edit</td>";
		echo "</tr>";
		
		$validMemberCount = 0;
		foreach($this->allMemberRecords as $key => $singleMember){
			if($singleMember->isValidMember()){
				$validMemberCount++;
				echo "<tr id='memberTableLineTr".$singleMember->ClubID."' 
						
						".(isset($memberTrClass)?"class='".($memberTrClass.(((in_array($this->managerClubID, $this->adminIds) || in_array($this->managerClubID, $this->superadminIds)) && $this->showAllMembers) ? 
						($singleMember->ClubID % 2) : ($validMemberCount % 2)))."'":"")." 
								
								onMouseOver=\"mouseOverorOutMemberLine('memberTableLineTr".
						$singleMember->ClubID."', '".(((in_array($this->managerClubID, $this->adminIds) || in_array($this->managerClubID, $this->superadminIds)) && $this->showAllMembers) ? 
						$singleMember->ClubID : $validMemberCount)."', true, true)\" 
								
								onMouseOut=\"mouseOverorOutMemberLine('memberTableLineTr".$singleMember->ClubID."', '".
						(((in_array($this->managerClubID, $this->adminIds) || in_array($this->managerClubID, $this->superadminIds)) && $this->showAllMembers) ? $singleMember->ClubID : $validMemberCount)."', false, true)\">".
						$singleMember->getSingleMemberLine("memberTableLineTr".$singleMember->ClubID, "memberTableLineTrEdit".$singleMember->ClubID, $this->isEditAble($key), $happyBirthdayId, $this->showAllMembers)."</tr>";
			} else {
				if(((in_array($this->managerClubID, $this->adminIds) || in_array($this->managerClubID, $this->superadminIds)) && $this->showAllMembers) || 
						(!(in_array($this->managerClubID, $this->adminIds) || in_array($this->managerClubID, $this->superadminIds)) && $this->managerClubID == $singleMember->ClubID && !$this->showAllMembers)){
					echo "<tr id='memberTableLineTr".$singleMember->ClubID."' ".(isset($inValidStatusClass)?"class='".($inValidStatusClass.($singleMember->ClubID % 2))."'":"").
						" onMouseOver=\"mouseOverorOutMemberLine('memberTableLineTr".$singleMember->ClubID."', '".$singleMember->ClubID."', true, false)\" onMouseOut=\"mouseOverorOutMemberLine('memberTableLineTr".$singleMember->ClubID."', '".$singleMember->ClubID."', false, false)\">".
						$singleMember->getSingleMemberLine("memberTableLineTr".$singleMember->ClubID, "memberTableLineTrEdit".$singleMember->ClubID, $this->isEditAble($key), $happyBirthdayId, $this->showAllMembers)."</tr>";
				}
			}
		}
		echo "</table>";
		
		if((in_array($this->managerClubID, $this->adminIds) || in_array($this->managerClubID, $this->superadminIds)) && $this->showAllMembers){
			$this->printCount(count($this->allMemberRecords));
		} else {
			$this->printCount($validMemberCount);
		}
	}
	
	private function printCount($countNum){
		echo "<div id='countNum'>";
		echo "<table id='countTable'>";
		echo "<tr><td>Total: ".$countNum.".</td><td id='countBacktoTop'><a href='javascript:void(0)' onClick='setToTop()'>[&nbsp;Back To Top&nbsp;]</a></td></tr>";
		echo "</table>";
		echo "</div>";
	}
	
	private function isEditAble($key){
		if(in_array($this->managerClubID, $this->superadminIds)){
			return MemberManager::CONST_EDIT_KEY_SUPER_ADMIN;
		} elseif(in_array($this->managerClubID, $this->adminIds)){
			return MemberManager::CONST_EDIT_KEY_NORMAL_ADMIN;
		} elseif(($this->allMemberRecords[$key]->ClubID == $this->managerClubID) && $this->allMemberRecords[$key]->isValidMember()){
			return MemberManager::CONST_EDIT_KEY_ONESELF;
		} else {
			return MemberManager::CONST_EDIT_KEY_NA;
		}
	}
	
	public function setShowMembersOption($showAllTag){
		$this->showAllMembers = $showAllTag;
	}
	
	public function getAllMembers(){
		return $this->allMemberRecords;
	}
	
	public function getValidMembers(){
		return $this->validMembers;
	}
	
	public function getValidMemberSelectionList($clubId, $typeOfRoleName, $selectId, $selectedAction){
		$returnString = "";
		
		if($selectedAction == null){
			$returnString = "<select id='".$selectId."' style='width:255px'>";
		} else {
			$returnString = "<select id='".$selectId."' style='width:255px' onChange='".$selectedAction."(\"".$selectId."\", \"".$typeOfRoleName."\")'>";
		}
		
		foreach ($this->validMembers as $singleMember){
			$returnString = $returnString."<option value='".$singleMember->ClubID."' ".(($singleMember->ClubID == $clubId)?"selected":"").">".($singleMember->ClubID.". ".$singleMember->getFormalFormatOfMemberName())."</option>";
		}
		$returnString = $returnString."</select>";
		
		return $returnString;
	}
}

/**
 * Test 
 */

?>