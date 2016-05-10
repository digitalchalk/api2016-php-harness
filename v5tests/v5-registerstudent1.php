<?php

$expecting = array('newuserid', 'offeringforuser');

$api = new APIv5($KEY_OAUTH2_PRIVATE, $TEST_API_HOST);

if(dataIsPresent($expecting)) {
	$newUserId = getTestDataKey('newuserid');
	$offeringId = getTestDataKey('offeringforuser');
	
	$postData = array(
		'userId' => $newUserId,
		'offeringId' => $offeringId	
	);
	
	$result = $api->apiPost('registrations', $postData);
	
	if(!empty($result['api_result']) && $result['api_result'] == 'success') {
		if(!empty($result['response_headers']['Location'])) {
			$location = $result['response_headers']['Location'];
			$lparts = explode('/', $location);
			$newId = array_pop($lparts);
			$refData['newregistrationid'] = $newId;
	
		}
	}	
	
} else {
	
	$testErrorMsg = 'Missing some expected data from set of : ' . print_r($expecting, true);
	
}

?>