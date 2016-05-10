<?php 
$expecting = array('knowncategory');

if(dataIsPresent($expecting)) {
	$catId = getTestDataKey('knowncategory');
	$api = new APIv5($KEY_OAUTH2_PRIVATE, $TEST_API_HOST);

	$result = $api->apiGetForId('offeringcategories', $catId);
	
	if(empty($result['results'][0])) {
		$testErrorMsg = 'Assertion failed: No data was returned.';
	}
} else {
	$testErrorMsg = 'Missing some expected data from set of : ' . print_r($expecting, true);	
}
?>