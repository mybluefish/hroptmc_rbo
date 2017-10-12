<?php

$filenameToInclude = array("sqlcfg.php", "sqlutil.php", "functions.php", "exceptionaldate.php");

$packageNames = array("classes", "functions");

for($includeFileIndex = 0; $includeFileIndex < count($filenameToInclude); $includeFileIndex++){
	for($packageIndex = 0; $packageIndex < count($packageNames); $packageIndex++){
		
		if(file_exists(dirname(__FILE__)."/".$filenameToInclude[$includeFileIndex])){
			require_once dirname(__FILE__)."/".$filenameToInclude[$includeFileIndex];
		} elseif(file_exists(dirname(__FILE__)."/".$packageNames[$packageIndex]."/".$filenameToInclude[$includeFileIndex])){
			require_once dirname(__FILE__)."/".$packageNames[$packageIndex]."/".$filenameToInclude[$includeFileIndex];
		} elseif (file_exists(dirname(dirname(__FILE__))."/".$packageNames[$packageIndex]."/".$filenameToInclude[$includeFileIndex])){
			require_once dirname(dirname(__FILE__))."/".$packageNames[$packageIndex]."/".$filenameToInclude[$includeFileIndex];
		}
		
	}
}



/**
 *  This is the MeetingDate class
 */
class MeetingDate{
	
	private $year;
	private $month;
	private $day;

	private $exceptionalDate;

	private $paramNames = array("year", "month", "day", "exceptionalDate");
	
	const NOT_REGULAR_KEY_NORMAL = 0;
	const NOT_REGULAR_KEY_CANCELLED = 1;
	const NOT_REGULAR_KEY_HELD_BUT_NOT_REGULAR = 2;
	
	const DATE_SPAN_IN_A_WEEK = 7;
	const THURSDAY_INDEX_IN_A_WEEK = 4;
	
	private function __construct($year, $month, $day, $isNext){
		$this->year = $year;
		$this->month = $month;
		$this->day = $day;	

		$this->exceptionalDate = new ExceptionalDate($this->toString());
		
		while($this->exceptionalDate->ifIsCancelled()){
			$tempYear = $this->year;
			$tempMonth = $this->month;
			$tempDay = $this->day;
			
			if($isNext){
				$this->year = getNextDate($tempYear, $tempMonth, $tempDay, MeetingDate::DATE_SPAN_IN_A_WEEK, "y");
				$this->month = getNextDate($tempYear, $tempMonth, $tempDay, MeetingDate::DATE_SPAN_IN_A_WEEK, "m");
				$this->day = getNextDate($tempYear, $tempMonth, $tempDay, MeetingDate::DATE_SPAN_IN_A_WEEK, "d");
			} else {
				$this->year = getLastDate($tempYear, $tempMonth, $tempDay, MeetingDate::DATE_SPAN_IN_A_WEEK, "y");
				$this->month = getLastDate($tempYear, $tempMonth, $tempDay, MeetingDate::DATE_SPAN_IN_A_WEEK, "m");
				$this->day = getLastDate($tempYear, $tempMonth, $tempDay, MeetingDate::DATE_SPAN_IN_A_WEEK, "d");
			}
			
			$this->exceptionalDate = new ExceptionalDate($this->toString());
		}
		
		
	}
	
	private function copyMeetingDate(MeetingDate $mDate){
		return new MeetingDate($mDate->year, $mDate->month, $mDate->day, true);
	}
	
	public static function getMeetingDateFromTimeStamp($timeStamp){
		return new MeetingDate(date("Y", $timeStamp), date("n", $timeStamp), date("j", $timeStamp), true);
	}

	public function __get($propName){
		if(in_array($propName, $this->paramNames)){
			return $this->$propName;
		}
	}

	public static function getCurrentMeetingDate(){
		$todayDateYear = date("Y");
		$todayDateMonth = date("n");
		$todayDateDay = date("j");

		$currentMeetingDateYear = $todayDateYear;
		$currentMeetingDateMonth = $todayDateMonth;
		$currentMeetingDateDay = $todayDateDay;

		$currentWeekdayIndex = date("N");

		if($currentWeekdayIndex < MeetingDate::THURSDAY_INDEX_IN_A_WEEK){
			$currentMeetingDateYear = getNextDate($todayDateYear, $todayDateMonth, $todayDateDay, MeetingDate::THURSDAY_INDEX_IN_A_WEEK - $currentWeekdayIndex, "y");
			$currentMeetingDateMonth = getNextDate($todayDateYear, $todayDateMonth, $todayDateDay, MeetingDate::THURSDAY_INDEX_IN_A_WEEK - $currentWeekdayIndex, "m");
			$currentMeetingDateDay = getNextDate($todayDateYear, $todayDateMonth, $todayDateDay, MeetingDate::THURSDAY_INDEX_IN_A_WEEK - $currentWeekdayIndex, "d");
		} elseif ($currentWeekdayIndex > MeetingDate::THURSDAY_INDEX_IN_A_WEEK) {
			$currentMeetingDateYear = getLastDate($todayDateYear, $todayDateMonth, $todayDateDay, $currentWeekdayIndex - MeetingDate::THURSDAY_INDEX_IN_A_WEEK, "y");
			$currentMeetingDateMonth = getLastDate($todayDateYear, $todayDateMonth, $todayDateDay, $currentWeekdayIndex - MeetingDate::THURSDAY_INDEX_IN_A_WEEK, "m");
			$currentMeetingDateDay = getLastDate($todayDateYear, $todayDateMonth, $todayDateDay, $currentWeekdayIndex - MeetingDate::THURSDAY_INDEX_IN_A_WEEK, "d");
		}
		
		return new MeetingDate($currentMeetingDateYear, $currentMeetingDateMonth, $currentMeetingDateDay, true);
	}

	public function  getLastMeetingDate(){
		return  new MeetingDate(getLastDate($this->year, $this->month, $this->day, MeetingDate::DATE_SPAN_IN_A_WEEK, "y"),
				getLastDate($this->year, $this->month, $this->day, MeetingDate::DATE_SPAN_IN_A_WEEK, "m"),
				getLastDate($this->year, $this->month, $this->day, MeetingDate::DATE_SPAN_IN_A_WEEK, "d"), false);
	}

	public function getNextMeetingDate(){
		return new MeetingDate(getNextDate($this->year, $this->month, $this->day, MeetingDate::DATE_SPAN_IN_A_WEEK, "y"),
				getNextDate($this->year, $this->month, $this->day, MeetingDate::DATE_SPAN_IN_A_WEEK, "m"),
				getNextDate($this->year, $this->month, $this->day, MeetingDate::DATE_SPAN_IN_A_WEEK, "d"), true);
	}
	
	public function getMeetingDateInTheFurture($inWeeks){
		$meetingDateInTheFuture = $this->copyMeetingDate($this);
		
		if($inWeeks < 0){
			die("Wrong format of week numbers in the future, at line ".__LINE__.", in file ".__FILE__);
		}
		
		if($inWeeks == 0){
			return $meetingDateInTheFuture;
		}
		
		for($index = 0; $index < $inWeeks; $index++){
			$meetingDateInTheFuture = $meetingDateInTheFuture->getNextMeetingDate();
		}
		
		return $meetingDateInTheFuture;
	}
	
	public function getMeetingDateInThePast($inWeeks){
		$meetingDateInThePast = $this->copyMeetingDate($this);
		
		if($inWeeks < 0){
			die("Wrong format of week numbers in the future, at line ".__LINE__.", in file ".__FILE__);
		}
		
		if($inWeeks == 0){
			return $meetingDateInThePast;
		}
		
		for($index = 0; $index < $inWeeks; $index++){
			$meetingDateInThePast = $meetingDateInThePast->getLastMeetingDate();
		}
		
		return $meetingDateInThePast;
	}

	public function toString(){
		return $this->year."-".$this->month."-".$this->day;
	}
	
	public function isInThePast(){
		$todayDateYear = date("Y");
		$todayDateMonth = date("n");
		$todayDateDay = date("j");
		
		if($this->year < $todayDateYear){
			return true;
		} elseif(($this->year == $todayDateYear) && ($this->month < $todayDateMonth)) {
			return true;
		} elseif (($this->year == $todayDateYear) && ($this->month == $todayDateMonth) && ($this->day <= $todayDateDay)){
			return true;
		} 
		
		return false;
	}
	
	
	public function isNormalMeeting(){
		return $this->exceptionalDate->isNormalMeeting();;
	}
	
	public function ifIsCancelled(){
		return $this->exceptionalDate->ifIsCancelled();
	}
	
	public function ifIsSpecial(){
		return $this->exceptionalDate->ifIsSpecial();
	}
	
	public function getExceptionalReason(){
		return $this->exceptionalDate->getExceptionalReason();
	}
	
	public static function isMeetingDateNewThanSplitDate($dateString){
		return (strtotime($dateString) > strtotime(RegularMeeting::SPILT_DATE_TIME));
	}
}
?>