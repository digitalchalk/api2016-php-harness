<?php
$expecting = array('newuserid', 'agreementtype');

$api = new APIv5($KEY_OAUTH2_PRIVATE, $TEST_API_HOST);

if(dataIsPresent($expecting)) {
	$newUserId = getTestDataKey('newuserid');
	$agreementType = getTestDataKey('agreementtype');

	$result = $api->apiGetForId('users/' . $newUserId . '/agreements', $agreementType);
	
	if(!empty($result['results'][0])) {
		$userAgreement = $result['results'][0];
		if(!$userAgreement['agreed']) {
			$testErrorMsg = 'Assertion failed: Expected agreement to be agreed == true';	
		}
	} else {
		$testErrorMsg = 'Assertion failed: No data was returned (expected 1 agreement)';
	}
	
} else {

	$testErrorMsg = 'Missing some expected data from set of : ' . print_r($expecting, true);

}
?>