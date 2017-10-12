<?php
class Navigator{
	private $index;
	private $subIndex;
	
	private $rootDir;
	
	private $navigationItemList = array();
	
	public function __construct($index, $subIndex, $rootDir){
		$this->index = $index;
		$this->subIndex = $subIndex;
		$this->rootDir = $rootDir;
		
		$this->navigationItemList = $this->getNavigationItemListFromDatabase();
	}
	
	private function getNavigationItemListFromDatabase(){
		$navigationItemList = array();
		$sqlLink = SqlUtil::getSqlConnection();
		$sqlSentence = "SELECT * FROM ".NavigationItem::TABLE_NAME.";";
		$queryObject = mysql_query($sqlSentence, $sqlLink);
		
		if(!$queryObject){
			return $navigationItemList;
		}
		
		if(mysql_num_rows($queryObject) == 0){
			return $navigationItemList;
		}
		
		while($fetchedDatabaseArrays = mysql_fetch_array($queryObject)){
			$singleNavigationItem = new NavigationItem($fetchedDatabaseArrays);
			array_push($navigationItemList, $singleNavigationItem);
		}
		
		return $navigationItemList;
	}
	
	public function printNavigator(){
		echo '<nav class="noFixed">';
		
		foreach($this->navigationItemList as $singleNavigationItem){
			$activeString = "inactive";
			if($this->isCurrentPage($singleNavigationItem)){
				$activeString = "active";
			}
			echo '<a href="'.$this->rootDir.'/'.$singleNavigationItem->pageurl.'" title="'.$singleNavigationItem->pagetitle.'" class="'.$activeString.'" target="_self">'.$singleNavigationItem->pagename.'</a>';
		}
		
		echo '</nav>';
	}
	
	private function isCurrentPage(NavigationItem $navigationItem){
		if(($navigationItem->index == $this->index) && ($navigationItem->subindex == $this->subIndex)){
			return true;
		}
		return false;
	}
}
?>