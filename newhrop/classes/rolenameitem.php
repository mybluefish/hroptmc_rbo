<?php
class RoleNameItem{	
	const TABLE_NAME = "rolenames";
	
	const NUMBER = "Number";
	const ROLE_KEY = "RoleKey";
	const ROLE_NAME = "RoleName";
	const CC_OR_CL_OLD = "CCorCLOld";
	const CC_OR_CL_MAR6 = "CCorCLMar6";
	const ALLOW_DUPLICATED = "AllowDuplicated";
	const SHOW_ORDER = "ShowOrder";
	const IS_SHOWN = "IsShown";
	
	const CC_STR = "CC";
	const CL_STR = "CL";
	const NO_CC_CL_STR = "NOCCCL";
	const THEME_STR = "THEME";
	const PRESIDENT_STR = "PRESIDENT";
	
	const nameArray = array(RoleNameItem::NUMBER, RoleNameItem::ROLE_KEY, RoleNameItem::ROLE_NAME, RoleNameItem::CC_OR_CL_OLD,
							RoleNameItem::CC_OR_CL_MAR6, RoleNameItem::ALLOW_DUPLICATED, RoleNameItem::SHOW_ORDER, RoleNameItem::IS_SHOWN);
	
	private $Number;
	private $RoleKey;
	private $RoleName;
	private $CCorCLOld;
	private $CCorCLMar6;
	private $AllowDuplicated;
	private $ShowOrder;
	private $IsShown;
	
	public function __construct(array $fetchedArray){
		foreach(RoleNameItem::nameArray as $valueOfTableKeyName){
			if(array_key_exists($valueOfTableKeyName, $fetchedArray)){
				$this->{$valueOfTableKeyName} = $fetchedArray[$valueOfTableKeyName];
			}
		}
	}
	
	public function __get($propName){
		if(in_array($propName, RoleNameItem::nameArray)){
			return $this->$propName;
		}
	}
	
	public function __set($propName, $value){
		if(in_array($propName, RoleNameItem::nameArray)){
			$this->$propName = $value;
		}
	}
}
?>