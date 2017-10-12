<?php
class MeetingDate{
	const YEAR = "year";
	const MONTH ="month";
	const DAY = "day";
	
	const nameArray = array(MeetingDate::YEAR, MeetingDate::MONTH, MeetingDate::DAY);
	
	private $year;
	private $month;
	private $day;
	
	const DAYS_IN_SINGLE_MONTH = array(31,0,31,30,31,30,31,31,30,31,30,31);
	const DAYS_IN_LEAP_MONTH = 29;
	const DAYS_IN_NON_LEAP_MONTH = 28;
	const JANURARY_INDEX = 1;
	const FEBRUARY_INDEX = 2;
	const DECEMBER_INDEX = 12;
	const YEAR_DURATION_OF_LEAP_YEAR = 4;
	
	const MEETING_INDEX_IN_A_WEEK = 4;
	const DAY_SPAN_IN_A_WEEK = 7;
	
	private function __construct($year, $month, $day){
		$this->year = $year;
		$this->month = $month;
		$this->day = $day;
	}
	
	public static function getCurrentMeetingDate(){
		$currentMeetingDateYear = date("Y");
		$currentMeetingDateMonth = date("n");
		$currentMeetingDateDay = date("j");
		
		$currentWeekdayIndex = date("N");
		
		return MeetingDate::getNextDate($currentMeetingDateYear, $currentMeetingDateMonth, $currentMeetingDateDay, MeetingDate::getNextDaySpanFromGivenDay($currentWeekdayIndex));
	}
	
	public function getNextMeetingDate(){
		return MeetingDate::getNextDate($this->year, $this->month, $this->day, MeetingDate::DAY_SPAN_IN_A_WEEK);
	}
	
	public function getLastMeetingDate(){
		return MeetingDate::getLastDate($this->year, $this->month, $this->day, MeetingDate::DAY_SPAN_IN_A_WEEK);
	}
	
	public function getNextMeetingDateCountByWeeks($weekNumber){
		return MeetingDate::getNextDate($this->year, $this->month, $this->day, MeetingDate::DAY_SPAN_IN_A_WEEK * $weekNumber);
	}
	
	public function getLastMeetingDateCountByWeeks($weekNumber){
		return MeetingDate::getLastDate($this->year, $this->month, $this->day, MeetingDate::DAY_SPAN_IN_A_WEEK * $weekNumber);
	}
	
	private static function getNextDate($year, $month, $day, $daySpan){
		if($daySpan < 0){
			$daySpan = MeetingDate::getNextDaySpanFromCurrent();
		}
		
		$maxDayInThisMonth = MeetingDate::getMaxDayInAMonth($year, $month);
		$day = $day + $daySpan;
		
		while($day > $maxDayInThisMonth){
			$day = $day - $maxDayInThisMonth;
			if(MeetingDate::isDecember($month)){
				$year++;
			}
			$month = MeetingDate::getNextMonth($month);
			$maxDayInThisMonth = MeetingDate::getMaxDayInAMonth($year, $month);
		}
		
		return new MeetingDate($year, $month, $day);
	}
	
	private static function getLastDate($year, $month, $day, $daySpan){
		if($daySpan < 0){
			$daySpan = MeetingDate::getLastDaySpanFromCurrent();
		}
		
		$day = $day - $daySpan;
		
		while($day <= 0){
			if(MeetingDate::isJanuary($month)){
				$year = $year - 1;
			}	
			$month = MeetingDate::getLastMonth($month);
			$day = MeetingDate::getMaxDayInAMonth($year, $month) + $day;
		}
		
		return new MeetingDate($year, $month, $day);
	}
	
	private static function getMaxDayInAMonth($year, $month){
		if($month != MeetingDate::FEBRUARY_INDEX){
			return MeetingDate::DAYS_IN_SINGLE_MONTH[$month - 1];
		}
		
		if(MeetingDate::isLeapYear($year)){
			return MeetingDate::DAYS_IN_LEAP_MONTH;
		} else {
			return MeetingDate::DAYS_IN_NON_LEAP_MONTH;
		}
	}
	
	public static function isLeapYear($year){
		if($year % MeetingDate::YEAR_DURATION_OF_LEAP_YEAR == 0){
			return true;
		} else {
			return false;
		}
	}
	
	public static function getNextMonth($month){
		if(MeetingDate::isDecember($month)){
			return MeetingDate::JANURARY_INDEX;
		} else {
			return $month + 1;
		}
	}
	
	public static function getLastMonth($month){
		if(MeetingDate::isJanuary($month)){
			return MeetingDate::DECEMBER_INDEX;
		} else {
			return $month - 1;
		}
	}
	
	public static function isJanuary($month){
		if($month == MeetingDate::JANURARY_INDEX){
			return true;
		} else {
			return false;
		}
	}

	public static function isDecember($month){
		if($month == MeetingDate::DECEMBER_INDEX){
			return true;
		} else {
			return false;
		}
	}
	
	private static function getNextDaySpanFromCurrent(){
		return MeetingDate::getNextDaySpanFromGivenDay(date("N"));
	}
	
	private static function getLastDaySpanFromCurrent(){
		return MeetingDate::getLastDaySpanFromGivenDay(date("N"));
	}
	
	private static function getNextDaySpanFromGivenDay($dayIndex){
		if($dayIndex <= MeetingDate::MEETING_INDEX_IN_A_WEEK){
			return MeetingDate::MEETING_INDEX_IN_A_WEEK - $dayIndex;
		} else {
			return MeetingDate::MEETING_INDEX_IN_A_WEEK + (MeetingDate::DAY_SPAN_IN_A_WEEK - $dayIndex);
		}
	}
	
	private static function getLastDaySpanFromGivenDay($dayIndex){
		if($dayIndex < MeetingDate::MEETING_INDEX_IN_A_WEEK){
			return MeetingDate::DAY_SPAN_IN_A_WEEK - (MeetingDate::MEETING_INDEX_IN_A_WEEK - $dayIndex);
		} else {
			return $dayIndex - MeetingDate::MEETING_INDEX_IN_A_WEEK;
		}
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
	
	public function toString(){
		return $this->year.'-'.$this->month.'-'.$this->day;
	}
	
	public function printMeetingDate(){
		echo 'Meeting Date: '.$this->toString().'<br>';
	}
}
?>