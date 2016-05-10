<?php 

$api = new APIv5($KEY_OAUTH2_PRIVATE, $TEST_API_HOST);

$result = $api->apiGetForList('offeringcategories');

if(empty($result['results']['0'])) {
	$testErrorMsg = 'ASSERTION FAILED: No results were returned';
} else {
	$refData['knowncategory'] = $result['results']['0']->id;
}

?>