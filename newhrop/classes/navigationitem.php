<?php
class NavigationItem{
	const TABLE_NAME = "navigationitems";
	
	const INDEX = "index";
	const SUB_INDEX = "subindex";
	const PAGE_NAME = "pagename";
	const PAGE_TITLE = "pagetitle";
	const PAGE_URL = "pageurl";
	const IS_SHOW = "isshow";
	
	const nameArray = array(NavigationItem::INDEX, NavigationItem::SUB_INDEX, NavigationItem::PAGE_NAME, 
							   NavigationItem::PAGE_TITLE, NavigationItem::PAGE_URL, NavigationItem::IS_SHOW);
							   
	private $index;
	private $subindex;
	private $pagename;
	private $pagetitle;
	private $pageurl;
	private $isshow;
	
	public function __construct(array $fetchedArray){
		foreach(NavigationItem::nameArray as $valueOfTableKeyName){
			if(array_key_exists($valueOfTableKeyName, $fetchedArray)){
				$this->{$valueOfTableKeyName} = $fetchedArray[$valueOfTableKeyName];
			}
		}
	}
	
	public function __get($propName){
		if(in_array($propName, NavigationItem::nameArray)){
			return $this->$propName;
		}
	}
	
	public function __set($propName, $value){
		if(in_array($propName, NavigationItem::nameArray)){
			$this->$propName = $value;
		}
	}
}
?>