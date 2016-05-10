<?php 


$api = new APIv5($KEY_OAUTH2_PRIVATE, $TEST_API_HOST);

$result = $api->apiGetForList('userfields');

if(empty($result['results'][0])) {
	$testErrorMsg = 'ASSERTION FAILED: No values were returned';
}

?>