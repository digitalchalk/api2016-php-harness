<?php 
$expecting = array("newuserid");

if(dataIsPresent($expecting)) {
	$newUserId = getTestDataKey('newuserid');
	$api = new APIv5($KEY_OAUTH2_PRIVATE, $TEST_API_HOST);

	$result = $api->apiGetForId('users', $newUserId);
	
	if(!empty($result['results'][0])) {
		$user = $result['results'][0];
		if(empty($user['firstName']) || $user['firstName'] != 'Automated-Edited') {
			$testErrorMsg = 'Assertion failed: firstName != Automated-Edited';
		}
	} else {
		$testErrorMsg = 'Assertion failed: No user data was returned.';
	}
} else {
	$testErrorMsg = 'Missing some expected data from set of : ' . print_r($expecting, true);	
}
?>
