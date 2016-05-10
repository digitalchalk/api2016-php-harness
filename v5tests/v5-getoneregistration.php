<?php

$expecting = array('newuserid', 'newregistrationid', 'offeringforuser');

$api = new APIv5($KEY_OAUTH2_PRIVATE, $TEST_API_HOST);

if(dataIsPresent($expecting)) {
	
	$newUserId = getTestDataKey('newuserid');
	$newRegistrationId = getTestDataKey('newregistrationid');
	$offeringId = getTestDataKey('offeringforuser');
	
	$result = $api->apiGetForId('registrations', $newRegistrationId);
	
	if(!empty($result['results'][0])) {
		$registration = $result['results'][0];
		if($registration['userId'] != $newUserId || $registration['offeringId'] != $offeringId) {
			$testErrorMsg = 'Test failed.  Expected userId ' . $newUserId . ' and offeringId ' . $offeringId;
		}
	} else {
		$testErrorMsg = 'Test failed.  No data was returned (expecting 1 registration)';
	}
	
} else {
	$testErrorMsg = 'Missing some expected data from set of : ' . print_r($expecting, true);
}
?>