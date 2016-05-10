<?php
$expecting = array("newuseremail");

if(dataIsPresent($expecting)) {
	$newUserEmail = getTestDataKey('newuseremail');
	$api = new APIv5($KEY_OAUTH2_PRIVATE, $TEST_API_HOST);

	$filter = array(
				'email' => 'automated' . $newUserEmail . '@digitalchalk.com'
			);
			
	$result = $api->apiGetForFilter('users', $filter);

	if(!empty($result['results'][0])) {
		$user = $result['results'][0];
		if(empty($user->firstName) || $user->firstName != 'Automated-Edited') {
			$testErrorMsg = 'Assertion failed: firstName != Automated-Edited';
		}
	} else {
		$testErrorMsg = 'Assertion failed: No user data was returned.';
	}
} else {
	$testErrorMsg = 'Missing some expected data from set of : ' . print_r($expecting, true);
}
?>