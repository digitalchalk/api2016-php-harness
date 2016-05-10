<?php 
$expecting = array('newuserid', 'agreementtype');

if(dataIsPresent($expecting)) {

	$api = new APIv5($KEY_OAUTH2_PRIVATE, $TEST_API_HOST);

	$userId = getTestDataKey('newuserid');
	$agreementType = getTestDataKey('agreementtype');

	$result = $api->apiGetForList('users/' . $userId . '/agreements');

	if(empty($result['results'][0])) {
		$testErrorMsg = 'Test failed.  No data was returned (expecting at least 1 agreement)';
	}
	
	foreach($result['results'] as $userAgreement) {
		if($userAgreement->agreed != FALSE) {
			$testErrorMsg = 'User should not have agreed already to type ' . $userAgreement->type;
		}
	}
	
} else {
	$testErrorMsg = 'Missing some expected data from set of : ' . print_r($expecting, true);
}
?>