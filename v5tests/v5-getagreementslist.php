<?php 

$api = new APIv5($KEY_OAUTH2_PRIVATE, $TEST_API_HOST);

$result = $api->apiGetForList('agreements');

if(empty($result['results'][0])) {
	$testErrorMsg = 'Test failed.  No data was returned (expecting at least 1 agreement)';
} else {
	$refData['agreementtype'] = $result['results'][0]->type;
}

?>