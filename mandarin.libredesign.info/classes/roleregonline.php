<?php

class RoleRegOnline{
	const NO_OF_AGENDAS_IN_ONE_SCREEN = 4;
	const CURRENT_DATE_CENTRAL_MEETING_DATE = 0;
	const CENTRAL_MEETING_ORDER = 0;
	const MAX_LEFT_TO_CENTRAL_MEETING_ORDER = 2;
	const MAX_RIGHT_TO_CENTRAL_MEETING_ORDER = 5;

	private $startMeetingDateOrder;

	private $roleNames;

	private $fileToLink;

	private $editAuthorized;

	private $regularMeetings = array();

	public function __construct($sMeetingDateOrder, RoleNames $rNames, $fToLink){
		 $this->startMeetingDateOrder = $sMeetingDateOrder + RoleRegOnline::CENTRAL_MEETING_ORDER;
		 $this->roleNames = $rNames;
		 $this->fileToLink = $fToLink;

		 /*
		  * This algorithm is for decide which one have the right to delete the registered users
		  */
		 if(isset($_SESSION["USERNAME"])){
		 	$this->editAuthorized = true;
		 } else {
		 	$this->editAuthorized = false;
		 }

		 $this->editAuthorized = true;

		 $currentMeetingDate = $this->getCentralMeetingDate();

		 if($this->isStartIdInThePast()){
		 	if($this->isStartIdOutOfRangeInThePast()){
		 		$this->handleRegularMeetingsOutRangeInThePast($currentMeetingDate);
		 	} else {
		 		$this->handleRegularMeetingsJustInThePast($currentMeetingDate);
		 	}
		 } else {
			$this->handleRegularMeetingsInTheFuture($currentMeetingDate);
		 }
	}

	/**
	 * Get the central meeting date according to the value of RoleRegOnline::CENTRAL_MEETING_ORDER
	 * 0 - This Week(currentMeetingDate), -1 - Last Week, 1 - Next Week
	 * @return Central Meeting Date
	 */
	private function getCentralMeetingDate(){

		$centralMeetingDate = MeetingDate::getCurrentMeetingDate();

		if($this->isCentralDateInTheFutureOfCurrentDate()){
			$centralMeetingDate = $centralMeetingDate->getMeetingDateInTheFurture(RoleRegOnline::CENTRAL_MEETING_ORDER - RoleRegOnline::CURRENT_DATE_CENTRAL_MEETING_DATE);
		} elseif($this->isCentralDateInThePastOfCurrentDate()){
			$centralMeetingDate = $centralMeetingDate->getMeetingDateInThePast(RoleRegOnline::CURRENT_DATE_CENTRAL_MEETING_DATE - RoleRegOnline::CENTRAL_MEETING_ORDER);
		}

		return $centralMeetingDate;
	}

	private function isCentralDateInTheFutureOfCurrentDate(){
		return (RoleRegOnline::CENTRAL_MEETING_ORDER > RoleRegOnline::CURRENT_DATE_CENTRAL_MEETING_DATE);
	}

	private function isCentralDateInThePastOfCurrentDate(){
		return (RoleRegOnline::CENTRAL_MEETING_ORDER < RoleRegOnline::CURRENT_DATE_CENTRAL_MEETING_DATE);
	}

	private function isStartIdInThePast(){
		return ($this->startMeetingDateOrder < RoleRegOnline::CENTRAL_MEETING_ORDER);
	}

	private function isStartIdOutOfRangeInThePast(){
		return (($this->startMeetingDateOrder + RoleRegOnline::NO_OF_AGENDAS_IN_ONE_SCREEN) <= RoleRegOnline::CENTRAL_MEETING_ORDER);
	}

	/**
	 * Handle Meetings far in the past
	 * @param MeetingDate $currentMeetingDate
	 */
	private function handleRegularMeetingsOutRangeInThePast(MeetingDate $currentMeetingDate){
		$lastMeetingDateInMainPage = $currentMeetingDate;

		for($index = RoleRegOnline::CENTRAL_MEETING_ORDER; $index > ($this->startMeetingDateOrder + RoleRegOnline::NO_OF_AGENDAS_IN_ONE_SCREEN); $index--){
			$lastMeetingDateInMainPage = $lastMeetingDateInMainPage->getLastMeetingDate();
		}

		for($regularMeetingIndex = RoleRegOnline::NO_OF_AGENDAS_IN_ONE_SCREEN; $regularMeetingIndex > 0; $regularMeetingIndex--){
			$lastMeetingDateInMainPage = $lastMeetingDateInMainPage->getLastMeetingDate();
			$this->regularMeetings[$regularMeetingIndex - 1] = new RegularMeeting($this->roleNames, $lastMeetingDateInMainPage, $this->editAuthorized);
		}
	}

	/**
	 * Handle Meetings just in the past
	 * @param MeetingDate $currentMeetingDate
	 */
	private function handleRegularMeetingsJustInThePast(MeetingDate $currentMeetingDate){
		$startMeetingDateInMainPage = $currentMeetingDate;

		for($startIndex = RoleRegOnline::CENTRAL_MEETING_ORDER - $this->startMeetingDateOrder; $startIndex < RoleRegOnline::NO_OF_AGENDAS_IN_ONE_SCREEN; $startIndex++){

			$this->regularMeetings[$startIndex] = new RegularMeeting($this->roleNames, $startMeetingDateInMainPage, $this->editAuthorized);
			$startMeetingDateInMainPage = $startMeetingDateInMainPage->getNextMeetingDate();
		}

		$startMeetingDateInMainPage = $currentMeetingDate->getLastMeetingDate();

		for($startIndex = RoleRegOnline::CENTRAL_MEETING_ORDER - $this->startMeetingDateOrder - 1; $startIndex >= 0; $startIndex--){
			$this->regularMeetings[$startIndex] = new RegularMeeting($this->roleNames, $startMeetingDateInMainPage, $this->editAuthorized);
			$startMeetingDateInMainPage = $startMeetingDateInMainPage->getLastMeetingDate();
		}
	}

	/**
	 * Handle Meetings in the future
	 * @param MeetingDate $currentMeetingDate
	 */
	private function handleRegularMeetingsInTheFuture(MeetingDate $currentMeetingDate){
		$startMeetingDateInMainPage = $currentMeetingDate;

		for($index = 0; $index < $this->startMeetingDateOrder - RoleRegOnline::CENTRAL_MEETING_ORDER; $index++){
			$startMeetingDateInMainPage = $startMeetingDateInMainPage->getNextMeetingDate();
		}

		for($startIndex = 0; $startIndex < RoleRegOnline::NO_OF_AGENDAS_IN_ONE_SCREEN; $startIndex++){
			$this->regularMeetings[$startIndex] = new RegularMeeting($this->roleNames, $startMeetingDateInMainPage, $this->editAuthorized);
			$startMeetingDateInMainPage = $startMeetingDateInMainPage->getNextMeetingDate();
		}
	}


	public function drawToPage($tableId, $roleNameClass, $roleClass){
		$this->printRoleRegTables($tableId, $roleNameClass, $roleClass);

		$this->printHiddenCentralMeetingOrder();

		$this->printNavigatorBar();
	}


	private function printRoleRegTables($tableId, $roleNameClass, $roleClass){
		$this->printTableOpenning($tableId);

		//Print Headers
		$this->printTableHeader($roleNameClass, $roleClass);

		//Print Bodies
		$this->printBodies($roleNameClass, $roleClass);

		$this->printTableClosing();
	}

	private function printTableOpenning($tableId){
		echo "<table ".(isset($tableId)?"id='".$tableId."'":"").">";
	}

	private function printTableClosing(){
		echo "</table>";
	}

	private function printTableHeader($roleNameClass, $roleClass){
		echo "<tr>".$this->roleNames->getOnlyHeader($roleNameClass, RoleRegOnline::NO_OF_AGENDAS_IN_ONE_SCREEN);

		for($index = 0; $index < RoleRegOnline::NO_OF_AGENDAS_IN_ONE_SCREEN; $index++){
			echo $this->regularMeetings[$index]->getOnlyMeetingDate($roleClass, RoleRegOnline::NO_OF_AGENDAS_IN_ONE_SCREEN, $index+1);
		}

		echo "</tr>";
	}

	private function printBodies($roleNameClass, $roleClass){
		for($roleNumindex = 0; $roleNumindex < $this->roleNames->getNumberOfRoles(); $roleNumindex++){
			echo "<tr>".$this->roleNames->getSingleRoleName($roleNameClass, $roleNumindex, RoleRegOnline::NO_OF_AGENDAS_IN_ONE_SCREEN);
			for($index = 0; $index < RoleRegOnline::NO_OF_AGENDAS_IN_ONE_SCREEN; $index++){
				echo $this->regularMeetings[$index]->getSingleRole($roleClass, $roleNumindex, RoleRegOnline::NO_OF_AGENDAS_IN_ONE_SCREEN, $index+1);
			}
			echo "</tr>";
		}
	}

	private function printHiddenCentralMeetingOrder(){
		echo "<input type='hidden' id='centralMeetingOrder' value='".$this->startMeetingDateOrder."' />";
	}

	private function printNavigatorBar(){
		echo "<div id='navigatorBar'>";

		if(RoleRegOnline::CENTRAL_MEETING_ORDER - $this->startMeetingDateOrder < RoleRegOnline::MAX_LEFT_TO_CENTRAL_MEETING_ORDER){
			echo "<a href='".$this->fileToLink."?id=".($this->startMeetingDateOrder - 1)."'>&lt;&lt; 上周</a>";
		} else {
			echo "&lt;&lt; 上周";
		}

		printSpace(5);

		if(RoleRegOnline::CENTRAL_MEETING_ORDER != $this->startMeetingDateOrder){
			echo "<a href='".$this->fileToLink."'>本周</a>";
		} else {
			echo "本周";
		}

		printSpace(5);

		if($this->startMeetingDateOrder - RoleRegOnline::CENTRAL_MEETING_ORDER < RoleRegOnline::MAX_RIGHT_TO_CENTRAL_MEETING_ORDER){
			echo "<a href='".$this->fileToLink."?id=".($this->startMeetingDateOrder + 1)."'>下周 &gt;&gt;</a>";
		} else {
			echo "下周 &gt;&gt;";
		}

		printSpace(20);

		echo "</div>";
	}
}

?>
