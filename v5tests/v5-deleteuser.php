<?php

$expecting = array("newuserid");

if(dataIsPresent($expecting)) {
	$newUserId = getTestDataKey('newuserid');
	$api = new APIv5($KEY_OAUTH2_PRIVATE, $TEST_API_HOST);

	$result = $api->apiDelete('users', $newUserId);

} else {
	$testErrorMsg = 'Missing some expected data from set of : ' . print_r($expecting, true);
}

?>