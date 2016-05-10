<?php
$expecting = array("offeringid");

if(dataIsPresent($expecting)) {
	$offeringId = getTestDataKey('offeringid');
	$api = new APIv5($KEY_OAUTH2_PRIVATE, $TEST_API_HOST);

	$result = $api->apiGetForId('offerings', $offeringId);

	if(empty($result['results'][0])) {
		$testErrorMsg = 'ASSERTION FAILED: No result was returned.';	
	}
} else {
	$testErrorMsg = 'Missing some expected data from set of : ' . print_r($expecting, true);
}
?>