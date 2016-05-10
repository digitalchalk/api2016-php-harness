<?php 


$api = new APIv5($KEY_OAUTH2_PRIVATE, $TEST_API_HOST);

$result = $api->apiGetForList('offerings');

if(empty($result['results']['0'])) {
	$testErrorMsg = 'ASSERTION FAILED: No results were returned.';
	//$refData['offeringid'] = '00000000541afa1f01541b4b016f0180';
} else {
	$refData['offeringid'] = $result['results']['0']->id;
}

?>