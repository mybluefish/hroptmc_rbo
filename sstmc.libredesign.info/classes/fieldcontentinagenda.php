<?php
class FieldContentInAgenda{
	const ROLE_TYPE_DATE = "date";
	
	const SPLIT_CHARACTER = "/";
	const MEMBER_TAG = "m";	
	const NONE_MEMBER_TAG = "n";
	
	private $isEmpty;
	
	private $meetingDate;
	private $roleType;
	private $isMember;
	private $theme;
	private $id;
	private $memberName;
	private $clubName;
	private $clubIndex;
	private $memberLevelInCurrent;
	
	private $cc;
	private $cl;
	
	private $projectNumber;
	
	private $inOldFormat;
	
	private function __construct($meetingDate, $roleType){
		
		$this->meetingDate = $meetingDate;
		
		$this->roleType = $roleType;
		
		$this->setMember(false);
		
		$this->setInOldFormat(false);
		
		$this->setEmpty(false);
	}
	
	public function setEmpty($isEmpty){
		$this->isEmpty = $isEmpty;
	}
	
	public function isEmpty(){
		return $this->isEmpty;
	}
	
	public function setMeetingDate($meetingDate){
		$this->meetingDate = $meetingDate;
	}
	
	public function getMeetingDate(){
		return $this->meetingDate;
	}
	
	public function setMember($isMember){
		$this->isMember = $isMember;
	}
	
	public function isMember(){
		return $this->isMember;
	}
	
	public function setTheme($theme){
		$this->theme = $theme;
	}
	
	public function getTheme(){
		return $this->theme;
	}
	
	public function setId($id){
		$this->id = $id;
	}
	
	public function getId(){
		return $this->id;
	}
	
	public function setMemberName($memberName){
		$this->memberName = $memberName;
	}
	
	public function getMemberName(){
		return $this->memberName;
	}
	
	public function setMemberLevelInCurrent($memberLevelInCurrent){
		$this->memberLevelInCurrent = $memberLevelInCurrent;
	}
	
	public function getMemberLevelInCurrent(){
		return $this->memberLevelInCurrent;
	}
	
	public function setClubName($clubName){
		$this->clubName = $clubName;
	}
	
	public function getClubName(){
		return $this->clubName;
	}
	
	public function setClubIndex($clubIndex){
		$this->clubIndex = $clubIndex;
	}
	
	public function getClubIndex(){
		return $this->clubIndex;
	}
	
	public function setClubNameAndIndexWithClubIndexForNonMember($clubIndex){
		$this->setClubIndex($clubIndex);
		$tempClubObject = TmcClub::getClubWithClubIndex($clubIndex);
		if($tempClubObject){
			$this->setClubName($tempClubObject->tmcclubshortname);
		}
	}
	
	public function setCurrentRoleLevel($roleLevel){
		if($this->roleType == RoleNames::CONST_VALUE_CC){
			$this->cc = $roleLevel;
		} elseif($this->roleType == RoleNames::CONST_VALUE_CL){
			$this->cl = $roleLevel;
		}
	}
	
	public function getCurrentRoleLevel(){
		if($this->roleType == RoleNames::CONST_VALUE_CC){
			return $this->cc;
		} elseif($this->roleType == RoleNames::CONST_VALUE_CL){
			return $this->cl;
		} 
	}
	
	public function setProjectNumber($projectNumber){
		$this->projectNumber = $projectNumber;
	}
	
	public function getProjectNumber(){
		return $this->projectNumber;
	}
	
	public function setInOldFormat($inOldFormat){
		$this->inOldFormat = $inOldFormat;
	}
	
	public function isInOldFormat(){
		return $this->inOldFormat;
	}
	
	/**
	 * Date after FieldContentInAgenda::SPILT_DATE_TIME will use new database format
	 *
	 * Format of speakers are
	 * 		 m/ClubID/Name/MemberLevel/CcLevel/ProjectNo for members,
	 *       n/ID/Name/MemberLevel/CcLevel/ProjectNo/ClubIndex for members from brother/sister clubs
	 *
	 * Format of non-speakers are
	 * 		 m/CLUBID/Name/MemberLevel/ClLevel/ProjectNo for members,
	 *       n/ID/Name/MemberLevel/ClLevel/ProjectNo/ClubIndex for members from brother/sister clubs
	 *
	 * Format of President & Workshopis & Motivator & SAA
	 * 		 m/CLUBID/Name/MemberLevel
	 * 		 n/ID/Name/MemberLevel/ClubIndex
	 *
	 * Format of THEME is
	 * 		 THEME
	 *
	 * Nothing special for theme
	 * @param unknown $content
	 */
	public static function getFieldContentInAgendaFromInfoProvided($meetingDate, $roleTypeName){
		$contentFieldType = FieldContentInAgenda::getContentFieldRoleTypeFromRegularRoleType($meetingDate, $roleTypeName);
		$tempFieldContentObject = new FieldContentInAgenda($meetingDate, $contentFieldType);
		$tempFieldContentObject->setEmpty(true);
		return $tempFieldContentObject;
	}
	
	/**
	 * This function is used to generate the data structure from database, with given field
	 * To align with old data structure, so new function added and also keep the old way
	 * @param unknown $meetingDate
	 * @param unknown $roleTypeName
	 * @param unknown $content
	 * @return Ambigous <NULL, FieldContentInAgenda>
	 */
	public static function getFieldContentInAgendaFromContent($meetingDate, $roleTypeName, $content){
		
		if($content == "" || $content == null || $content == "NULL"){
			$contentFieldType = FieldContentInAgenda::getContentFieldRoleTypeFromRegularRoleType($meetingDate, $roleTypeName);
			$tempFieldContentObject = new  FieldContentInAgenda($meetingDate, $contentFieldType);
			$tempFieldContentObject->setEmpty(true);
			return $tempFieldContentObject;
		}
		
		if(FieldContentInAgenda::isContentInOldFormat($roleTypeName, $content)){
			return FieldContentInAgenda::getFieldContentInAgendaFromContentBeforeSplitDate($meetingDate, $roleTypeName, $content);
		} else {
			return FieldContentInAgenda::getFieldContentInAgendaFromContentAfterSplitDate($meetingDate, $roleTypeName, $content);
		}
		
// 		if(MeetingDate::isMeetingDateNewThanSplitDate($meetingDate)){
// 			if(FieldContentInAgenda::isContentInOldFormat($roleTypeName, $content)){
// 				return FieldContentInAgenda::getFieldContentInAgendaFromContentBeforeSplitDate($meetingDate, $roleTypeName, $content);
// 			} else {
// 				return FieldContentInAgenda::getFieldContentInAgendaFromContentAfterSplitDate($meetingDate, $roleTypeName, $content);
// 			}
// 		} else {
// 			return FieldContentInAgenda::getFieldContentInAgendaFromContentBeforeSplitDate($meetingDate, $roleTypeName, $content);
// 		}
	}
	
	/**
	 * Date before FieldContentInAgenda::SPILT_DATE_TIME will use old database format
	 *
	 * Format of speakers are
	 * 		 m/Name/MemberLevel/CcLevel/ProjectNo for members,
	 *       n/Name/MemberLevel/clubName/CcLevel/ProjectNo for members from brother/sister clubs
	 *
	 * Format of non-speakers & Workshopis & Motivator & SAA & JOKE are
	 * 		 m/Name/MemberLevel/ for members,
	 *       n/Name/MemberLevel/ClubName for members from brother/sister clubs
	 *
	 * Format of President 
	 * 		 m/Name/MemberLevel
	 *
	 * Format of THEME is
	 * 		 THEME
	 *
	 * Nothing special for theme
	 * @param unknown $content
	 */
	private static function getFieldContentInAgendaFromContentBeforeSplitDate($meetingDate, $roleTypeName, $content){
		
		$tempFieldContentInAgendaObject = null;
		
		$splitedContent = explode(FieldContentInAgenda::SPLIT_CHARACTER, $content);
		
		if(RoleNames::isTheme($roleTypeName)){
			// FieldContentInAgenda::NO_OF_PARTS_THEME is 1
			$tempFieldContentInAgendaObject = new FieldContentInAgenda($meetingDate, RoleNames::CONST_VALUE_THEME);
			$tempFieldContentInAgendaObject->setTheme($content);
		} else {
			if(RoleNames::isRoleTypeNoSpeakerInOldFormat($roleTypeName)){
				
				$tempFieldContentInAgendaObject = new FieldContentInAgenda($meetingDate, RoleNames::CONST_VALUE_NO_CC_CL);
				
				if(!FieldContentInAgenda::isMemberFormatContent($content)){
					$tempFieldContentInAgendaObject->setClubName($splitedContent[3]);
				}
			} elseif (RoleNames::isRoleTypeCC($roleTypeName)){
				
				$tempFieldContentInAgendaObject = new FieldContentInAgenda($meetingDate, RoleNames::CONST_VALUE_CC);
				
				// For member, index 3 and 4 are are roleLevel and projectNumber
				// and non member index 4 and 5 are roleLevel and projectNumber,and 3 club name or other or guest
				if(FieldContentInAgenda::isMemberFormatContent($content)){
					$tempFieldContentInAgendaObject->setCurrentRoleLevel($splitedContent[3]);
					$tempFieldContentInAgendaObject->setProjectNumber($splitedContent[4]);
				} else {
					$tempFieldContentInAgendaObject->setClubName($splitedContent[3]);
					$tempFieldContentInAgendaObject->setCurrentRoleLevel($splitedContent[4]);
					$tempFieldContentInAgendaObject->setProjectNumber($splitedContent[5]);
				}
			}
			
			if($tempFieldContentInAgendaObject != null){
				if(FieldContentInAgenda::isMemberFormatContent($content)){
					$tempFieldContentInAgendaObject->setMember(true);
				}
				$tempFieldContentInAgendaObject->setMemberName($splitedContent[1]);
				$tempFieldContentInAgendaObject->setMemberLevelInCurrent($splitedContent[2]);
				
				$tempFieldContentInAgendaObject->setInOldFormat(true);
			}
		}

		return $tempFieldContentInAgendaObject;
	}
	
	
	/**
	 * Date after FieldContentInAgenda::SPILT_DATE_TIME will use new database format
	 * 
	 * Format of speakers are 
	 * 		 m/ClubID/Name/MemberLevel/CcLevel/ProjectNo for members, 
	 *       n/ID/Name/MemberLevel/CcLevel/ProjectNo/ClubIndex for members from brother/sister clubs
	 *       
	 * Format of non-speakers are 
	 * 		 m/CLUBID/Name/MemberLevel/ClLevel/ProjectNo for members, 
	 *       n/ID/Name/MemberLevel/ClLevel/ProjectNo/ClubIndex for members from brother/sister clubs
	 *       
	 * Format of President & Workshopis & Motivator & SAA & JOKE
	 * 		 m/CLUBID/Name/MemberLevel
	 * 		 n/ID/Name/MemberLevel/ClubIndex
	 * 
	 * Format of THEME is
	 * 		 THEME
	 * 
	 * Nothing special for theme
	 * @param unknown $content
	 */
	private static function getFieldContentInAgendaFromContentAfterSplitDate($meetingDate, $roleTypeName, $content){
		
		
		$tempFieldContentInAgendaObject = null;
		
		$splitedContent = explode(FieldContentInAgenda::SPLIT_CHARACTER, $content);
		
		if(RoleNames::isTheme($roleTypeName)){
			// FieldContentInAgenda::NO_OF_PARTS_THEME is 1
			$tempFieldContentInAgendaObject = new FieldContentInAgenda($meetingDate, RoleNames::CONST_VALUE_THEME);
			$tempFieldContentInAgendaObject->setTheme($content);
		} else{
			if(RoleNames::isRoleTypeNoCCAndNoCL($roleTypeName)){
				
				$tempFieldContentInAgendaObject = new FieldContentInAgenda($meetingDate, RoleNames::CONST_VALUE_NO_CC_CL);
				
				if(!FieldContentInAgenda::isMemberFormatContent($content)){
					$tempFieldContentInAgendaObject->setClubNameAndIndexWithClubIndexForNonMember($splitedContent[4]);
				}
			
			} elseif(RoleNames::isRoleTypeCCOrCL($roleTypeName)){
			
				if(RoleNames::isRoleTypeCC($roleTypeName)){
					$tempFieldContentInAgendaObject = new FieldContentInAgenda($meetingDate, RoleNames::CONST_VALUE_CC);
				} else {
					$tempFieldContentInAgendaObject = new FieldContentInAgenda($meetingDate, RoleNames::CONST_VALUE_CL);
				}
				
				// index 4 and 5 are roleLevel and projectNumber
				// and non member index 6 are club name or other or guest
				$tempFieldContentInAgendaObject->setCurrentRoleLevel($splitedContent[4]);
				$tempFieldContentInAgendaObject->setProjectNumber($splitedContent[5]);
				if(!FieldContentInAgenda::isMemberFormatContent($content)){
					$tempFieldContentInAgendaObject->setClubNameAndIndexWithClubIndexForNonMember($splitedContent[6]);
				}
			} else {
				exit;
			}
			
			if($tempFieldContentInAgendaObject != null){
				if(FieldContentInAgenda::isMemberFormatContent($content)){
					$tempFieldContentInAgendaObject->setMember(true);
				}
				$tempFieldContentInAgendaObject->setId($splitedContent[1]);
				$tempFieldContentInAgendaObject->setMemberName($splitedContent[2]);
				$tempFieldContentInAgendaObject->setMemberLevelInCurrent($splitedContent[3]);
			}
			
// 			if($splitedContent[0] != FieldContentInAgenda::MEMBER_TAG){
// 				$clubObject = TmcClub::getClubWithClubIndex($tempFieldContentInAgendaObject->getClubIndex());
			
// 				if($clubObject){
// 					$tempFieldContentInAgendaObject->setClubName($clubObject->tmcclubname);
// 				} else {
// 					$tempFieldContentInAgendaObject->setClubName(TmcClub::CONST_TMC_CLUB_DEFAULT);
// 				}
// 			}
		}

		return $tempFieldContentInAgendaObject;
	}
	
	
	public function getPrintedFormat(){
		$formattedName = "";
		
		if($this->isEmpty()){
			return $formattedName;
		}
		
		if($this->roleType == RoleNames::CONST_VALUE_THEME){
			$formattedName = $this->getTheme();
		} elseif ($this->roleType == RoleNames::CONST_VALUE_NO_CC_CL){
			$formattedName = $this->getMemberName()." (".$this->getMemberLevelInCurrent().($this->isMember()?"":("&nbsp;".$this->getClubName())).")";
		} elseif (($this->roleType == RoleNames::CONST_VALUE_CC) || ($this->roleType == RoleNames::CONST_VALUE_CL)){
			$formattedName = $this->getMemberName()." (".$this->getMemberLevelInCurrent().($this->isMember()?"":("&nbsp;".$this->getClubName())).") / ".$this->getCurrentRoleLevel()." P".$this->getProjectNumber();
		} else {
			$formattedName = "-";
		}
		
		return $formattedName;
	}
	
	/**
	 * Date after FieldContentInAgenda::SPILT_DATE_TIME will use new database format
	 *
	 * Format of speakers are
	 * 		 m/ClubID/Name/MemberLevel/CcLevel/ProjectNo for members,
	 *       n/ID/Name/MemberLevel/CcLevel/ProjectNo/ClubName for members from brother/sister clubs
	 *
	 * Format of non-speakers are
	 * 		 m/CLUBID/Name/MemberLevel/ClLevel/ProjectNo for members,
	 *       n/ID/Name/MemberLevel/ClLevel/ProjectNo/ClubName for members from brother/sister clubs
	 *
	 * Format of President & Workshopis & Motivator & SAA & JOKE
	 * 		 m/CLUBID/Name/MemberLevel
	 * 		 n/ID/Name/MemberLevel/ClubName
	 *
	 * Format of THEME is
	 * 		 THEME
	 *
	 * Nothing special for theme
	 */
	public function getDatabaseFormat(){
		$formattedName = "";
		
		if($this->isEmpty()){
			return $formattedName;
		}
		
		if($this->roleType == RoleNames::CONST_VALUE_THEME){
			$formattedName = $this->getTheme();
		} else {
			if($this->isMember()){
				$formattedName = FieldContentInAgenda::MEMBER_TAG;
			} else {
				$formattedName = FieldContentInAgenda::NONE_MEMBER_TAG;
			}
			
			$formattedName = $formattedName.FieldContentInAgenda::SPLIT_CHARACTER.
							 $this->getId().FieldContentInAgenda::SPLIT_CHARACTER.
							 $this->getMemberName().FieldContentInAgenda::SPLIT_CHARACTER.
							 $this->getMemberLevelInCurrent();
			
			if($this->roleType != RoleNames::CONST_VALUE_NO_CC_CL){
				$formattedName = $formattedName.FieldContentInAgenda::SPLIT_CHARACTER.
								 $this->getCurrentRoleLevel().FieldContentInAgenda::SPLIT_CHARACTER.
								 $this->getProjectNumber();
			}
			
			if(!$this->isMember()){
				$formattedName = $formattedName.FieldContentInAgenda::SPLIT_CHARACTER.$this->getClubIndex();
			}
		}
		
		return $formattedName;
	}
	
	public function isSameMemberInAgenda($memberIdentify){
		if($this->isInOldFormat()){
			return ($this->memberName == $memberIdentify);
		} else {
			return ($this->id == $memberIdentify);
		}
		
	}
	
	/**
	 * Will distinguish the format through content parse
	 * @param unknown $meetingDate
	 * @param unknown $roleType
	 * @return string
	 */
	public static function getContentFieldRoleTypeFromRegularRoleType($meetingDate, $roleType){
		if(RoleNames::isTheme($roleType)){
			return RoleNames::CONST_VALUE_THEME;
		} elseif(RoleNames::isRoleTypeCC($roleType)) {
			return RoleNames::CONST_VALUE_CC;
// 		} elseif((strtotime($meetingDate) > strtotime(RegularMeeting::SPILT_DATE_TIME)) && RoleNames::isRoleTypeCL($roleType)){
		} elseif(RoleNames::isRoleTypeCL($roleType)){
			return RoleNames::CONST_VALUE_CL;
		} else {
			return RoleNames::CONST_VALUE_NO_CC_CL;
		}
	}
	
	public static function isMemberFormatContent($content){
		$isMemberFormatContent = false;
		
		$splitedContents = explode(FieldContentInAgenda::SPLIT_CHARACTER, $content);
		
		if(($splitedContents > 0) && ($splitedContents[0] == FieldContentInAgenda::MEMBER_TAG)){
			$isMemberFormatContent = true;
		}
		
		return $isMemberFormatContent;
	}
	
	
	/**
	 * Date before FieldContentInAgenda::SPILT_DATE_TIME will use old database format
	 *
	 * Format of speakers are
	 * 		 m/Name/MemberLevel/CcLevel/ProjectNo for members,
	 *       n/Name/MemberLevel/clubName/CcLevel/ProjectNo for members from brother/sister clubs
	 *
	 * Format of non-speakers
	 * 
	 *			 Workshopis & Motivator & SAA & JOKE are
	 * 		 m/Name/MemberLevel/ for members,
	 *       n/Name/MemberLevel/ClubName for members from brother/sister clubs
	 *
	 * Format of President
	 * 		 m/Name/MemberLevel
	 *
	 *  NEW
	 *
	 ** Format of speakers are 
	 * 		 m/ClubID/Name/MemberLevel/CcLevel/ProjectNo for members, 
	 *       n/ID/Name/MemberLevel/CcLevel/ProjectNo/ClubIndex for members from brother/sister clubs
	 *       
	 * Format of non-speakers are 
	 * 		 m/CLUBID/Name/MemberLevel/ClLevel/ProjectNo for members, 
	 *       n/ID/Name/MemberLevel/ClLevel/ProjectNo/ClubIndex for members from brother/sister clubs
	 *       
	 * Format of President & Workshopis & Motivator & SAA & JOKE
	 * 		 m/CLUBID/Name/MemberLevel
	 * 		 n/ID/Name/MemberLevel/ClubIndex
	 * 

	 * Nothing special for theme
	 * @param unknown $content
	 */
	public static function isContentInOldFormat($roleTypeName, $content){
		$isContentInOldFormat = false;
		
		$splitedContent = explode(FieldContentInAgenda::SPLIT_CHARACTER, $content);
		
		if(RoleNames::isRoleTypeCC($roleTypeName)){
			if((count($splitedContent) == RoleNames::CONST_NUM_OLD_CC_MEMBER) || 
					((!FieldContentInAgenda::isMemberFormatContent($content)) && (count($splitedContent) == RoleNames::CONST_NUM_OLD_CC_NON_MEMBER))){
				$isContentInOldFormat = true;
			}
		} elseif(RoleNames::isRoleTypeCL($roleTypeName)){
			if((count($splitedContent) == RoleNames::CONST_NUM_OLD_NO_CC_MEMBER) ||
					(count($splitedContent) == RoleNames::CONST_NUM_OLD_NO_CC_NONE_MEMBER)){
				$isContentInOldFormat = true;
			}
		} elseif(RoleNames::isRoleTypeNoCCAndNoCL($roleTypeName)){
			if((count($splitedContent) == RoleNames::CONST_NUM_OLD_NO_CC_MEMBER) ||
					((!FieldContentInAgenda::isMemberFormatContent($content)) && (count($splitedContent) == RoleNames::CONST_NUM_OLD_NO_CC_NONE_MEMBER))){
				$isContentInOldFormat = true;
			}
		}
		
		return $isContentInOldFormat;
	}
}
?>