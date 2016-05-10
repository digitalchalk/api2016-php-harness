<?php 

$refData = array();
$postData = array();
$expectFailure = FALSE;

function getTestRefData() {
	$result = array();
	if(!empty($_SESSION['testRefData'])) {
		$result = $_SESSION['testRefData'];
	}
	return $result;
}

function setTestRefData($refData) {
	if(!empty($refData)) {	
		$_SESSION['testRefData'] = $refData;
	} else {
		$_SESSION['testRefData'] = array();
	}
}

function dataIsPresent($data) {
	$refData = getTestRefData();
	foreach($data as $key) {
		if(empty($refData[$key])) {
			return FALSE;
		}
	}
	return TRUE;
}

function getTestDataKey($key) {
	$refData = getTestRefData();
	if(!empty($refData[$key])) {
		return $refData[$key];
	} else {
		return null;
	}
}

function randomString($length) {
	$r1 = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $length-2);
	$r2 = substr(str_shuffle('01234567890'),0,2);
	return str_shuffle($r1 . $r2);

}

function addRefData($refData) {
	if(!empty($refData)) {
		$masterRefData = getTestRefData();
		foreach($refData as $key => $val) {
			$masterRefData[$key] = $val;
		}
		setTestRefData( $masterRefData );
	}
}

?>