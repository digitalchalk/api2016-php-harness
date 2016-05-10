<?php 


$newEmail = randomString(10);
$newPassword = randomString(10);

$refData['newuseremail'] = $newEmail;

$api = new APIv5($KEY_OAUTH2_PRIVATE, $TEST_API_HOST);

$user = array(
		'firstName' => 'Automated',
		'lastName' => 'TestUser',
		'username' => 'automated' . $newEmail . '@digitalchalk.com',
		'email' => 'automated' . $newEmail . '@digitalchalk.com',
		'password' => $newPassword,
		'locale' => 'en'
);

$postData = $user;

$result = $api->apiPost('users', $user);

if(!empty($result['api_result']) && $result['api_result'] == 'success') {
	if(!empty($result['response_headers']['Location'])) {
		$location = $result['response_headers']['Location'];
		$lparts = explode('/', $location);
		$newId = array_pop($lparts);
		$refData['newuserid'] = $newId;
		
	}
}

?>
