<?php

class Contest{
    
    private $contestIndex;
    private $contestTitle;
    private $language;
    private $speechDate;
    
    private $names = array("contestIndex", "contestTitle", "language", "speechDate");
    
    public static function getContestInstance($contestIndex, $contestTitle, $language, $speechDate){
        $contestInstance = new Contest();

        $contestInstance->contestIndex = $contestIndex;
        $contestInstance->contestTitle = $contestTitle;
        $contestInstance->language = $language;
        $contestInstance->speechDate = $speechDate;
        
        return $contestInstance;
    }
    
    /**
     * Getter, to get the parametr of a Member Object
     * @param unknown_type $propName
     */
    public function __get($propName){
        if(in_array($propName, $this->names)){
            return $this->$propName;
        }
    }
    
    /**
     * Setter, to set the value of parameter
     * @param unknown_type $propName
     * @param unknown_type $value
     */
    public function __set($propName, $value){
        if(in_array($propName, $this->names)){
            $this->$propName = $value;
        }
    }
   
}

?>