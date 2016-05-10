<?php

$expecting = array('newuserid', 'agreementtype');

$api = new APIv5($KEY_OAUTH2_PRIVATE, $TEST_API_HOST);

if(dataIsPresent($expecting)) {
	$newUserId = getTestDataKey('newuserid');
	$agreementType = getTestDataKey('agreementtype');
	
	$postData = array(
		'agreed' => TRUE
	);
	
	$result = $api->apiPut('users/' . $newUserId . '/agreements' , $agreementType, $postData);
	
} else {
	
	$testErrorMsg = 'Missing some expected data from set of : ' . print_r($expecting, true);
	
}

?>