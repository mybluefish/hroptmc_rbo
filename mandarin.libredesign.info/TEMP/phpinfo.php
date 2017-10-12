<?php
$value;

if(isset($value)){
	echo "yes";
} else {
	echo "no";
}
//$value = "dddxxxxxxd ";
//echo $value;

if(isset($value) ){
	$value1 = trim($value);
	if(!empty($value1)){
		echo "here";
	}
}
?>