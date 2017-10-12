<?php

/**
* array to store days from Jan to Dec, Feb is a special month as it may have 28 or 29 days, leave it to functions to solve.
*/
$daysInSingleMonth = array(31,0,31,30,31,30,31,31,30,31,30,31);

/**
* This method is to return the next regular meeting date of patthern $pattern from the date given
* Example, when giving 2013-1-9(Wednesday) and pattern "d"(day), it returns 3, but all dates are caculated
* @param $dateSpan is the date span from today to Thursday
*/
function getLastDate($year, $month, $day, $dateSpan, $pattern){
	global $daysInSingleMonth;

	if($day - $dateSpan > 0){
		return getDateAccordingtoPattern($year, $month, $day - $dateSpan, $pattern);
	} elseif (($month - 1) > 0) {
		if(($month - 1) == 2){
			return getDateAccordingtoPattern($year, $month - 1, (($year%4==0)?29:28) + $day -$dateSpan, $pattern);
		} else {
			return getDateAccordingtoPattern($year, $month - 1, $daysInSingleMonth[$month - 2] + $day - $dateSpan, $pattern);
		}
	} else{
		return getDateAccordingtoPattern($year - 1, 12, $day + 31 - $dateSpan, $pattern);  // December always has 31 days
	}
}

/**
* This method is to return the next regular meeting date of patthern $pattern from the date given
* Example, when giving 2013-1-9(Wednesday) and pattern "d"(day), it returns 10, but all dates are caculated
* @param $dateSpan is the date span from today to Thursday
*/
function getNextDate($year, $month, $day, $dateSpan, $pattern){
	global $daysInSingleMonth;

	if($day + $dateSpan > getMaxDaysInCurrentMonth($year, $month)){
		$day = $day + $dateSpan - getMaxDaysInCurrentMonth($year, $month);
		$month++;
		if($month > 12){
			$month %= 12;
			$year++;
		}
	} else {
		$day += $dateSpan;
	}
	return getDateAccordingtoPattern($year, $month, $day, $pattern);
}

/**
* return date according to pattern
*/
function getDateAccordingtoPattern($year, $month, $day, $pattern){
	if($pattern=="y"){
		return $year;
	} elseif ($pattern == "m") {
		return $month;
	} elseif ($pattern == "d") {
		return $day;
	}
}

/**
* Return number of days in coresponding month and year.
* Feb is special as it has 28 or 29 days.
*/
function getMaxDaysInCurrentMonth($year, $month){
	global $daysInSingleMonth;
	$maxDaysInCurrentMonth = 0;

	if($month == 2){
		if($year%4 == 0){
			$maxDaysInCurrentMonth = 29;
		} else {
			$maxDaysInCurrentMonth = 28;
		}
	} else {
		$maxDaysInCurrentMonth = $daysInSingleMonth[$month - 1];
	}

	return $maxDaysInCurrentMonth;
}

/**
* ToString function for date
*/
function dateToString($year, $month, $day){
	return $year."-".$month."-".$day;
}


function getMysqlConnection($dbHostName, $dbUserName, $dbPassword, $dbNameUsed){
	$sqlLink = mysql_connect($dbHostName, $dbUserName, $dbPassword);
	
	if( $sqlLink ){
		if(mysql_select_db($dbNameUsed, $sqlLink)){
			return $sqlLink;
		} else {
			return false;
		}
	} else {
		return false;
	}
	
}

function echoSqlConnectionErrors(){
	echo "Fatal MySQL Errors Happen.";
	echo "Something might be wrong!! Please try it again!";
	echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"2; URL=index.php\">";
	echo "Wait 2 seconds to redirect to main page, or click <a href=\"index.php\">here</a>";
}

/**
 * Get IP address that log on and log off.
 * @return unknown
 */
function getIP() {
	if (getenv('HTTP_CLIENT_IP')) {
		$ip = getenv('HTTP_CLIENT_IP');
	} 
	elseif (getenv('HTTP_X_FORWARDED_FOR')) {
		$ip = getenv('HTTP_X_FORWARDED_FOR');
	} 
	elseif (getenv('HTTP_X_FORWARDED')) {
		$ip = getenv('HTTP_X_FORWARDED');
	}
	elseif (getenv('HTTP_FORWARDED_FOR')) {
		$ip = getenv('HTTP_FORWARDED_FOR');

	}
	elseif (getenv('HTTP_FORWARDED')) {
		$ip = getenv('HTTP_FORWARDED');
	}
	else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	
	return $ip;
}

function dateCompare($date1, $date2){
     
    $date1Array = explode("-", $date1);
    $date2Array = explode("-", $date2);
     
    if((count($date1Array) != 3) || (count($date2Array) != 3)){
        return 0;
    }
     
    for($i = 0; $i < count($date1Array); $i++){
        if($date1Array[$i] > $date2Array[$i]){
             
            return 1;
        } elseif ($date1Array[$i] < $date2Array[$i]){
             
            return -1;
        } else {
            continue;
        }
    }
     
    return 0;
}

/*
****These code are for testing of these functions****
*
*/
?>