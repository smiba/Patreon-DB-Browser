<?php

function getInFullDollar($amount){
	if(strlen($amount) > 2){
	$first = substr($amount, 0, -2);
	$last = substr($amount, -2);
	$amount = "$first,$last";
	}elseif (strlen($amount) > 1){
		$amount = "0,$amount";
	}else{
		$amount = "0,0$amount";
	}
	
	return $amount;
}

?>