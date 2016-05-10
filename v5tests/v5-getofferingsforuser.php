<?php 
$expecting = array("newuserid");

if(dataIsPresent($expecting)) {
	$newUserId = getTestDataKey('newuserid');
	$api = new APIv5($KEY_OAUTH2_PRIVATE, $TEST_API_HOST);

	$filter = array(
			'userId' => $newUserId
	);
		
	$result = $api->apiGetForFilter('offerings', $filter);

	if(!empty($result['results'][0])) {
		$offering = $result['results'][0];
		$refData['offeringforuser'] = $offering->id;
	} else {
		$testErrorMsg = 'Assertion failed: No offering data was returned.';
	}
} else {
	$testErrorMsg = 'Missing some expected data from set of : ' . print_r($expecting, true);
}
?>
