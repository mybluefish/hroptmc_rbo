<?php
class RegularMeeting{
	private $meetingDate;
	
	const TABLE_NAME = "meetingagendaroles";
	const DATE = "date";
	const PRESIDENT = "president";
	
	private $dataArray = array(); 
	
	public function __construct(MeetingDate $meetingDate){
		$this->meetingDate = $meetingDate;
		$this->initRegularMeeting();
	}
	
	private function initRegularMeeting(){
		if(!$this->isNormalMeeting()){
			$this->deleteMeetingRecord();
			return;
		}
		
		if(!$this->isRecordExist()){
			$this->addNewMeetingRecord();
		}
		
		$this->dataArray = $this->getRegularMeetingArrayFromDatabase();
	}
	
	private function getRegularMeetingArrayFromDatabase(){
		$sqlLink = SqlUtil::getSqlConnection();
		$sqlSentence = "SELECT * FROM ".RegularMeeting::TABLE_NAME." WHERE ".RegularMeeting::DATE."='".$this->meetingDate->toString()."';";
		$queryObject = mysql_query($sqlSentence, $sqlLink);
		if(mysql_num_rows($queryObject) == 0){
			return array();
		} else {
			return mysql_fetch_array($queryObject);
		}
	}
	
	private function isRecordExist(){
		$sqlLink = SqlUtil::getSqlConnection();
		$sqlSentence = "SELECT * FROM ".RegularMeeting::TABLE_NAME." WHERE ".RegularMeeting::DATE."='".$this->meetingDate->toString()."';";
		$queryObject = mysql_query($sqlSentence, $sqlLink);
		if(mysql_num_rows($queryObject) == 0){
			return false;
		} else {
			return true;
		}
	}
	
	private function deleteMeetingRecord(){
		$sqlLink = SqlUtil::getSqlConnection();
		$sqlSentence = "DELETE FROM ".RegularMeeting::TABLE_NAME." WHERE ".RegularMeeting::DATE."='".$this->meetingDate->toString()."';";
		$queryObject = mysql_query($sqlSentence, $sqlLink);
	}
	
	private function addNewMeetingRecord(){
		$sqlLink = SqlUtil::getSqlConnection();
		$presidentData = $this->getPresidentInsertData();
		$sqlSentence = "INSERT INTO  `".RegularMeeting::TABLE_NAME."` (`".RegularMeeting::DATE."`, `".RegularMeeting::PRESIDENT."`) VALUES ('".$this->meetingDate->toString()."', '".$presidentData."');";
		$queryObject = mysql_query($sqlSentence, $sqlLink);
		return $queryObject;
	}
	
	private function getPresidentInsertData(){
		return "~~";
	}
	
	public function isNormalMeeting(){
		return true;
	}
	
	public function getMeetingDateString(){
		return $this->meetingDate->toString();
	}
	
	private function getRoleValuesByRoleKey($roleKey){
		if(array_key_exists($roleKey, $this->dataArray)){
			return $this->dataArray[$roleKey];
		} else {
			return "";
		}
	}
	
	public function getPrintedRoleValue($roleKey, RoleNames $roleNamesObj){
		$fieldContentStr = $this->getRoleValuesByRoleKey($roleKey);
		$fieldContentObj = new FieldContentInAgenda($roleKey, $fieldContentStr, $roleNamesObj, $this->meetingDate);
		return $fieldContentObj->getPrintedFormatStr();
	}
	
	public function printRegularMeeting(){
		foreach($this->dataArray as $key => $value){
			echo $key.' => '.$value.'<br>';
		}
	}
}
?>