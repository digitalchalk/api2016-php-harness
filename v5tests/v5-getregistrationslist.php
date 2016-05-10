<?php 

	$api = new APIv5($KEY_OAUTH2_PRIVATE, $TEST_API_HOST);

	$api->autoNext = FALSE;
	
	$result = $api->apiGetForFilter('registrations', array('limit' => 100));

	if(empty($result['results'][0])) {
		$testErrorMsg = 'Test failed.  No data was returned (expecting at least 1 registration)';
	}

?>