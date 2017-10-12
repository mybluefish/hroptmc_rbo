<?php
class ToastMastersClub{
	const TABLE_NAME = "tmcclubs";
	
	const TMC_CLUB_INDEX = "tmcclubindex";
	const TMC_CLUB_NAME = "tmcclubname";
	const TMC_CLUB_SHORT_NAME = "tmcclubshortname";
	const VALID = "valid";
	const VALID_DATE = "validdate";
	const EXPIRE_DATE = "expiredate";
	const TMC_CLUB_DESCRIPTION = "tmcclubdescription";
	
	const nameArray = array(ToastMastersClub::TMC_CLUB_INDEX, ToastMastersClub::TMC_CLUB_NAME, ToastMastersClub::TMC_CLUB_SHORT_NAME, ToastMastersClub::VALID,
							ToastMastersClub::VALID_DATE, ToastMastersClub::EXPIRE_DATE, ToastMastersClub::TMC_CLUB_DESCRIPTION);
							
	private $tmcclubindex;
	private $tmcclubname;
	private $tmcclubshortname;
	private $valid;
	private $validdate;
	private $expiredate;
	private $tmcclubdescription;
	
	public function __construct(array $fetchedArray){
		foreach(ToastMastersClub::nameArray as $valueOfTableKeyName){
			if(array_key_exists($valueOfTableKeyName, $fetchedArray)){
				$this->{$valueOfTableKeyName} = $fetchedArray[$valueOfTableKeyName];
			}
		}
	}
	
	public function __get($propName){
		if(in_array($propName, ToastMastersClub::nameArray)){
			return $this->$propName;
		}
	}
	
	public function __set($propName, $value){
		if(in_array($propName, ToastMastersClub::nameArray)){
			$this->$propName = $value;
		}
	}
}
?>