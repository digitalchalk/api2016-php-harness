<?php

$expecting = array('agreementtype');

if(dataIsPresent($expecting)) {

	$api = new APIv5($KEY_OAUTH2_PRIVATE, $TEST_API_HOST);
	
	$agreementType = getTestDataKey('agreementtype');
	
	$result = $api->apiGetForId('agreements', $agreementType);
	
	if(empty($result['results'][0])) {
		$testErrorMsg = 'Test failed.  No data was returned (expecting at least 1 agreement)';
	}
} else {
	$testErrorMsg = 'Missing some expected data from set of : ' . print_r($expecting, true);
}
?>