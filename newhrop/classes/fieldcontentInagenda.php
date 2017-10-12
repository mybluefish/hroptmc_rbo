<?php
class FieldContentInAgenda{
	const SPLIT_CHARACTER = "/";
	const MEMBER_TAG = "m";	
	const NONE_MEMBER_TAG = "n";
	const EMPTY_STR = "-";
	const PROJECT_PREFIX = "P";
	
	private $isNewFormat;
	private $roleType;
	private $filedContent;
	private $roleNamesObj;
	private $meetingDate;
	
	private $isEmpty;
	
	private $isMember;
	
	private $id;
	private $name;
	private $memberLevel;
	private $ccclLevel;
	private $projectNo;
	private $clubIndex;
	private $clubName;
	
	private $isTheme;
	private $isPresident;
	
	private $formattedStr;
	
	public function __construct($roleType, $filedContent, RoleNames $roleNamesObj, MeetingDate $meetingDate){
		$this->roleType = $roleType;
		$this->filedContent = $filedContent;
		$this->roleNamesObj = $roleNamesObj;
		$this->meetingDate = $meetingDate;
		
		$this->formattedStr = FieldContentInAgenda::EMPTY_STR;
		
		$this->parseFieldContent();
	}
	
	private function parseFieldContent(){
		$this->isEmpty = $this->getEmptyStatusFromContent();
		if($this->isEmpty){
			return;
		}
		
		$this->isNewFormat = $this->getFormatStatusFromContent();
		if($this->isNewFormat){
			$this->parseContentInNewFormat();
		} else {
			$this->parseContentInOldFormat();
		}
	}
	
	public function isEmpty(){
		return $this->isEmpty;
	}
	
	public function isNewFormat(){
		return $this->isNewFormat;
	}
	
	private function getEmptyStatusFromContent(){
		if($this->filedContent == "" || $this->filedContent == null || $this->filedContent == "NULL"){
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Date before FieldContentInAgenda::SPILT_DATE_TIME will use old database format
	 *
	 * OLD
	 *
	 * Format of speakers are
	 * 5	  m/Name/MemberLevel/CcLevel/ProjectNo for members,
	 * 6      n/Name/MemberLevel/clubName/CcLevel/ProjectNo for members from brother/sister clubs
	 *
	 * Format of non-speakers, Workshopis & Motivator & SAA & JOKE are
	 * 3	  m/Name/MemberLevel/ for members,
	 * 4      n/Name/MemberLevel/ClubName for members from brother/sister clubs
	 *
	 * Format of President
	 * 3 	  m/Name/MemberLevel
	 *
	 * Format of Theme
	 * 1      Theme
	 *
	 *  NEW
	 *
	 ** Format of speakers are 
	 * 6	 m/ClubID/Name/MemberLevel/CcLevel/ProjectNo for members, 
	 * 7     n/ID/Name/MemberLevel/CcLevel/ProjectNo/ClubIndex for members from brother/sister clubs
	 *       
	 * Format of non-speakers are 
	 * 6	 m/CLUBID/Name/MemberLevel/ClLevel/ProjectNo for members, 
	 * 7     n/ID/Name/MemberLevel/ClLevel/ProjectNo/ClubIndex for members from brother/sister clubs
	 *       e.g  1. m/4/Jerry Jiang/CC, CL/CL/1 => Jerry Jiang (CC, CL) / CL P1
	 *            2. n/130/JennyYang/TM/CL/1/6 => JennyYang (TM ET) / CL P1
	 *
	 * Format of President & Workshopis & Motivator & SAA & JOKE
	 * 4	 m/CLUBID/Name/MemberLevel
	 * 5	 n/ID/Name/MemberLevel/ClubIndex
	 * 
	 *
	 *Format of Theme
	 *1      Theme
	 *
	 * Nothing special for theme
	 * @param unknown $content
	 */
	 private function getFormatStatusFromContent(){
		 if(EducationalUtil::isRoleTypeTheme($this->roleType, $this->roleNamesObj)){
			return true;
		 }
		 
		 $contentArray = explode(FieldContentInAgenda::SPLIT_CHARACTER, $this->filedContent);
		 
		 if(EducationalUtil::isRoleTypeCC($this->roleType, $this->roleNamesObj)){
			$NUM_OF_PIECES_FOR_MEMBER_OLD = 5;
			$NUM_OF_PIECES_FOR_NON_MEMBER_OLD = 6;
			if(((count($contentArray) == $NUM_OF_PIECES_FOR_MEMBER_OLD) && ($contentArray[0] == FieldContentInAgenda::MEMBER_TAG)) ||
			    ((count($contentArray) == $NUM_OF_PIECES_FOR_NON_MEMBER_OLD) && ($contentArray[0] == FieldContentInAgenda::NONE_MEMBER_TAG))){
				return false;
			} else {
				return true;
			}
		 } else {
			$NUM_OF_PIECES_FOR_MEMBER_OLD = 3;
			$NUM_OF_PIECES_FOR_NON_MEMBER_OLD = 4;
			
			if(((count($contentArray) == $NUM_OF_PIECES_FOR_MEMBER_OLD) && ($contentArray[0] == FieldContentInAgenda::MEMBER_TAG)) ||
			    ((count($contentArray) == $NUM_OF_PIECES_FOR_NON_MEMBER_OLD) && ($contentArray[0] == FieldContentInAgenda::NONE_MEMBER_TAG))){
				return false;
			} else {
				return true;
			}
		 }
	 }
	 
	 /**
	 *  NEW
	 *
	 * Format of speakers are 
	 * 6	 m/ClubID/Name/MemberLevel/CcLevel/ProjectNo for members, 
	 * 7     n/ID/Name/MemberLevel/CcLevel/ProjectNo/ClubIndex for members from brother/sister clubs
	 *       
	 * Format of non-speakers are 
	 * 6	 m/CLUBID/Name/MemberLevel/ClLevel/ProjectNo for members, 
	 * 7     n/ID/Name/MemberLevel/ClLevel/ProjectNo/ClubIndex for members from brother/sister clubs
	 *       e.g  1. m/4/Jerry Jiang/CC, CL/CL/1 => Jerry Jiang (CC, CL) / CL P1
	 *            2. n/130/JennyYang/TM/CL/1/6 => JennyYang (TM ET) / CL P1
	 *
	 * Format of President & Workshopis & Motivator & SAA & JOKE
	 * 4	 m/CLUBID/Name/MemberLevel
	 * 5	 n/ID/Name/MemberLevel/ClubIndex
	 * 
	 *
	 *Format of Theme
	 *1      Theme
	 *
	 */
	 private function parseContentInNewFormat(){
		 $contentArray = explode(FieldContentInAgenda::SPLIT_CHARACTER, $this->filedContent);
		 
		 if(EducationalUtil::isRoleTypeTheme($this->roleType, $this->roleNamesObj)){
		 	$this->formattedStr = $this->filedContent;
		 } elseif(EducationalUtil::isRoleTypeCCorCL($this->roleType, $this->roleNamesObj)){
			
			$this->isMember = $this->isStartedWithMemberTag($contentArray[0]);			
			$this->id = $contentArray[1];
			$this->name = $contentArray[2];
			$this->memberLevel = $contentArray[3];
			$this->ccclLevel = $contentArray[4];
			$this->projectNo = $contentArray[5];
			
			if(!$this->isMember){
				$this->clubIndex = $contentArray[6];
				$this->clubName = $this->getClubShortNameByIndex();
			}
			
			$this->formattedStr = $this->getFormattedPrintedStrForCCCL();

		 } else {
			if(EducationalUtil::isRoleTypePresident($this->roleType, $this->roleNamesObj)){
				$NUM_OF_PIECES_FOR_PRESIDENT = 4;
				if(count($contentArray) != $NUM_OF_PIECES_FOR_PRESIDENT){
					return;
				}
			}
			
			$this->isMember = $this->isStartedWithMemberTag($contentArray[0]);
			$this->id = $contentArray[1];
			$this->name = $contentArray[2];
			$this->memberLevel = $contentArray[3];
			
			if(!$this->isMember){
				$this->clubIndex = $contentArray[4];
				$this->clubName = $this->getClubShortNameByIndex();
			}
			
			$this->formattedStr = $this->getFormattedPrintedStrForNOCCCL();
		 }
	 }

	 private function getFormattedPrintedStrForCCCL(){
		 $baseFormattedStr = $this->getBaseFormattedStr();
		 return $baseFormattedStr."&nbsp;".FieldContentInAgenda::SPLIT_CHARACTER."&nbsp;".$this->ccclLevel."&nbsp;".FieldContentInAgenda::PROJECT_PREFIX.$this->projectNo;
	 }
	 
	 private function getFormattedPrintedStrForNOCCCL(){
		 return $this->getBaseFormattedStr();
	 }
	 
	 private function getBaseFormattedStr(){
		 if($this->isMember){
			 return $this->name."&nbsp;(".$this->memberLevel.")";
		 } else {
			 return $this->name."&nbsp;(".$this->memberLevel."&nbsp;".$this->clubName.")";
		 }
	 }
	 
	 private function getClubShortNameByIndex(){
		 $clubsInstance = Clubs::getInstance();
		 return $clubsInstance->getClubObjByClubIndex($this->clubIndex, ToastMastersClub::TMC_CLUB_SHORT_NAME);
	 }
	 
	 /**
	 * Date before FieldContentInAgenda::SPILT_DATE_TIME will use old database format
	 *
	 * Format of speakers are
	 * 5	  m/Name/MemberLevel/CcLevel/ProjectNo for members,
	 * 6      n/Name/MemberLevel/clubName/CcLevel/ProjectNo for members from brother/sister clubs
	 *
	 * Format of non-speakers, Workshopis & Motivator & SAA & JOKE are
	 * 3	  m/Name/MemberLevel/ for members,
	 * 4      n/Name/MemberLevel/ClubName for members from brother/sister clubs
	 *
	 * Format of President
	 * 3 	  m/Name/MemberLevel
	 *
	 * Format of Theme
	 * 1      Theme
	 */
	 private function parseContentInOldFormat(){
		 $contentArray = explode(FieldContentInAgenda::SPLIT_CHARACTER, $this->filedContent);
		 if(EducationalUtil::isRoleTypeTheme($this->roleType, $this->roleNamesObj)){
		 	$this->formattedStr = $this->filedContent;
		 } elseif(EducationalUtil::isRoleTypeCC($this->roleType, $this->roleNamesObj)){ 
			$this->isMember = $this->isStartedWithMemberTag($contentArray[0]);	
			$this->name = $contentArray[1];
			$this->memberLevel = $contentArray[2];
			
			if($this->isMember){
				$this->ccclLevel = $contentArray[3];
				$this->projectNo = $contentArray[4];
			} else {
				$this->clubName = $contentArray[3];
				$this->ccclLevel = $contentArray[4];
				$this->projectNo = $contentArray[5];
			}
			
			$this->formattedStr = $this->getFormattedPrintedStrForCCCL();
			
		 } else {
			$this->isMember = $this->isStartedWithMemberTag($contentArray[0]);
			$this->name = $contentArray[1];
			$this->memberLevel = $contentArray[2];
			
			if(!$this->isMember){
				$this->clubName = $contentArray[3];
			}
			
			$this->formattedStr = $this->getFormattedPrintedStrForNOCCCL();
		 }
	 }
	 
	 private function isStartedWithMemberTag($extractedTag){
		 if($extractedTag == FieldContentInAgenda::MEMBER_TAG){
			 return true;
		 } else {
			 return false;
		 }
	 }
	 
	 public function getPrintedFormatStr(){
		 if($this->isEmpty()){
			 if(EducationalUtil::isRoleTypePresident($this->roleType, $this->roleNamesObj) || $this->meetingDate->isInThePast()){
				 return FieldContentInAgenda::EMPTY_STR;
			 } else {
			 	return "<a href='javascript:void(0)' onClick=\"regRole('".$this->meetingDate->toString()."','".$this->roleType."',event)\">Reserve</a>";
			 }
		 } else {
			 return $this->formattedStr;
		 }
	 }
	 

}
?>