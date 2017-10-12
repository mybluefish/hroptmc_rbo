<?php
class RBO{
	const RBO_TABLE_HEADER = "FUNCTIONS / MEETING DATES";
	
	const NO_OF_AGENDAS_IN_ONE_SCREEN = 4;
	const CENTRAL_MEETING_INDEX = 0;
	
	private $regularMeetingList = array();
	private $startIndex;
	private $startMeetingDate;
	private $roleNames;
	private $toastMastersClub;
	private $regPromptDivId;
	
	public function __construct($startIndex, RoleNames $roleNames, $regPromptDivId){
		$this->startIndex = $startIndex;
		$this->roleNames = $roleNames;
		$this->regPromptDivId = $regPromptDivId;
		
		$this->startMeetingDate = $this->getStartMeetingDate();
		
		for($index = 0; $index < RBO::NO_OF_AGENDAS_IN_ONE_SCREEN; $index++){
			$this->regularMeetingList[$index] = new RegularMeeting($this->startMeetingDate->getNextMeetingDateCountByWeeks($index));
		}
	}
	
	private function getStartMeetingDate(){
		$startMeetingDate = $this->getCentralMeetingDate();
		
		if($this->startIndex > RBO::CENTRAL_MEETING_INDEX){
			$startMeetingDate = $startMeetingDate->getNextMeetingDateCountByWeeks($this->startIndex - RBO::CENTRAL_MEETING_INDEX);
		} elseif($this->startIndex < RBO::CENTRAL_MEETING_INDEX){
			$startMeetingDate = $startMeetingDate->getLastMeetingDateCountByWeeks(RBO::CENTRAL_MEETING_INDEX - $this->startIndex);
		}
		
		return $startMeetingDate;
	}
	
	private function getCentralMeetingDate(){
		return MeetingDate::getCurrentMeetingDate();
	}
	
	public function printRboTable($tableTitle){
		$this->importRboCss();
		$this->printJavascrriptBody();
		
		echo '<table id="mainAgendasTable">';
		$this->printTableHeader($tableTitle);
		$this->printTableBody();
		echo '</table>';
	}
	
	private function printTableHeader($tableTitle){
		echo '<tr><td colspan="'.(count($this->regularMeetingList)+1).'" class="rboTableTitle">'.$tableTitle.'</td></tr>';
		echo '<tr>';
		echo '<td class="roleNameCol">'.RBO::RBO_TABLE_HEADER.'</td>';
		foreach($this->regularMeetingList as $key => $singleRegularMeeting){
			echo '<td class="meetingDateHeader meetingAgendaRole">'.$singleRegularMeeting->getMeetingDateString().'</td>';
		}
		echo '</tr>';
	}
	
	private function printTableBody(){
		for($index = 0; $index < $this->roleNames->getNumberOfRoleNames(); $index++){
			$roleName = $this->roleNames->getRoleNameProperty($index, RoleNameItem::ROLE_NAME);
			$roleKey = $this->roleNames->getRoleNameProperty($index, RoleNameItem::ROLE_KEY);
			
			echo '<tr>';
			echo '<td>'.$roleName.'</td>';
			foreach($this->regularMeetingList as $key => $singleRegularMeeting){
				echo '<td>'.$singleRegularMeeting->getPrintedRoleValue($roleKey, $this->roleNames).'</td>';
			}
			echo '</tr>';
		}
	}
	
	private function importRboCss(){
		echo '<link rel="stylesheet" href="./style/rbo.css" type="text/css" media="all" />';
	}
	
	private function printJavascrriptBody(){
		echo '
			<script type="text/javascript">
				function regRole(meetingDateStr, roleType, event){
					$.ajax({
						type: "POST",
						url: "util/regrole.php",
						data:{meetingdate: meetingDateStr, rolename: roleType},
						async: false,
						statusCode: {
							404: function() {
				    			alert("It seems something wrong happens in server side, please contact tojiangkun@gmail.com to fix it!!");
							}
						},
						success: function(data, textStatus){
							showRegRolePropmpt(data, event);
						}
					});
				}
				
				function showRegRolePropmpt(responseText, event){
					$("#'.$this->regPromptDivId.'").html(responseText);
					
					var leftedge    = document.documentElement.clientWidth-event.clientX;
					var bottomedge  = document.documentElement.clientHeight-event.clientY;
					
					if(leftedge < $("#'.$this->regPromptDivId.'").width()){
						leftedge = (document.documentElement.scrollLeft + event.pageX - $("#'.$this->regPromptDivId.'").width()) + "px";
					} else {
						leftedge = (document.documentElement.scrollLeft + event.pageX) + "px";
					}
					
					if(bottomedge < $("#'.$this->regPromptDivId.'").height()){
						bottomedge = (document.documentElement.scrollTop + event.pageY - $("#'.$this->regPromptDivId.'").height()) + "px";
					} else {
						bottomedge = (document.documentElement.scrollTop + event.pageY) + "px";
					}
					
					$("#'.$this->regPromptDivId.'").css({"left": leftedge, "top": bottomedge});
					$("#'.$this->regPromptDivId.'").addClass("regBorder");
				}
				
			</script>
		';
	}
	
	public function simplePrint(){
		foreach($this->regularMeetingList as $singleMeeting){
			$singleMeeting->printRegularMeeting();
		}
	}
}
?>