<?php

class ExceptionalDate{
	private $dateString;
	private $notRegularKey;
	private $exceptionalReason;
	
	// KEY NUMBER, 0 -> NORMAL MEETING, 1 -> METTING CANCELLED, 2 -> MEETING HELD BUT SPECIAL
	const NOT_REGULAR_KEY_NORMAL = 0;
	const NOT_REGULAR_KEY_CANCELLED = 1;
	const NOT_REGULAR_KEY_HELD_BUT_NOT_REGULAR = 2;
	
	const DB_NAME =  "exceptiondate";
	
	//fieldNames in table DB exceptiondate
	const EXCEPTIONALDATE_FIELD_NAME_DATE = "Date";
	const EXCEPTIONALDATE_FIELD_NAME_NOTREGULARKEY = "NotRegularKey";
	const EXCEPTIONALDATE_FIELD_NAME_REASON = "Reason";
	
	public function __construct($meetingDateString){
		$this->dateString = $meetingDateString;
		
		$sqlLink = SqlCfg::getSqlConnection();
		$exceptionalDateSqlObject = SqlUtil::getFetchObjectByGivenField(ExceptionalDate::EXCEPTIONALDATE_FIELD_NAME_DATE, $this->dateString, ExceptionalDate::DB_NAME, $sqlLink);
				
		if($exceptionalDateSqlObject){
			$this->notRegularKey = $exceptionalDateSqlObject->{ExceptionalDate::EXCEPTIONALDATE_FIELD_NAME_NOTREGULARKEY};
			$this->exceptionalReason = $exceptionalDateSqlObject->{ExceptionalDate::EXCEPTIONALDATE_FIELD_NAME_REASON};
		} else {
			$this->notRegularKey = ExceptionalDate::NOT_REGULAR_KEY_NORMAL;
			$this->exceptionalReason = "N.A";
		}
		
		mysql_close($sqlLink);
	}
	
	public function getDate(){
		return $this->dateString;
	}
	
	public function getNotRegularKey(){
		return $this->notRegularKey;
	}
	
	public function getExceptionalReason(){
		return $this->exceptionalReason;
	}
	
	public function isNormalMeeting(){
		return ($this->notRegularKey == ExceptionalDate::NOT_REGULAR_KEY_NORMAL);
	}
	
	public function ifIsCancelled(){
		return ($this->notRegularKey == ExceptionalDate::NOT_REGULAR_KEY_CANCELLED);
	}
	
	public function ifIsSpecial(){
		return ($this->notRegularKey == ExceptionalDate::NOT_REGULAR_KEY_HELD_BUT_NOT_REGULAR);
	}
}

?>