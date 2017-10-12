<?php
class EducationalUtil{
	
	//const SPEAKER_PREFIX = "speaker";
	//const IE_PREFIX = "evaluator";	
	
	public static function isRoleTypeCC($roleType, RoleNames $roleNamesObj){
		return EducationalUtil::isRoleTypeMatch($roleType, $roleNamesObj, RoleNameItem::CC_STR);
	}
	
	public static function isRoleTypeCL($roleType, RoleNames $roleNamesObj){
		return EducationalUtil::isRoleTypeMatch($roleType, $roleNamesObj, RoleNameItem::CL_STR);
	}
	
	public static function isRoleTypeCCorCL($roleType, RoleNames $roleNamesObj){
		return EducationalUtil::isRoleTypeCC($roleType, $roleNamesObj) || EducationalUtil::isRoleTypeCL($roleType, $roleNamesObj);
	}
	
	public static function isRoleTypeTheme($roleType, RoleNames $roleNamesObj){
		return EducationalUtil::isRoleTypeMatch($roleType, $roleNamesObj, RoleNameItem::THEME_STR);
	}
	
	public static function isRoleTypePresident($roleType, RoleNames $roleNamesObj){
		return EducationalUtil::isRoleTypeMatch($roleType, $roleNamesObj, RoleNameItem::PRESIDENT_STR);
	}
	
	private static function isRoleTypeMatch($roleType, RoleNames $roleNamesObj, $CC_CL_STR){
		for($i = 0; $i < $roleNamesObj->getNumberOfRoleNames(); $i++){
			$targetRoleType = $roleNamesObj->getRoleNameProperty($i, RoleNameItem::ROLE_KEY);
			$ccclStr = $roleNamesObj->getRoleNameProperty($i, RoleNameItem::CC_OR_CL_MAR6);
			if(EducationalUtil::isSame($roleType, $targetRoleType) && EducationalUtil::isSame($ccclStr, $CC_CL_STR)){
				return true;
			}
		}
		return false;
	}
	
	private static function isSame($toFound, $target){
		return strcmp($toFound, $target) == 0;
	}
}
?>