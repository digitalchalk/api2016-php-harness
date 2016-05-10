<?php 

require_once 'apikeys.php';
require_once 'testfunctions.php';
require_once 'testdata.php';
require_once 'apiv4.class.php';


$defaultSocketTimeout = ini_get('default_socket_timeout');
echo 'Default socket timeout = ' . $defaultSocketTimeout . '<br>';

$starttime = time();

$api = new APIv4($KEY_SOAP_PUBLIC, $KEY_SOAP_PRIVATE, $TEST_API_HOST);

$response = $api->getAvailableOfferings();

$endtime = time();

echo '<br>Start time = ' . $starttime . '<br>';
echo 'End time = ' . $endtime . '<br>';
echo 'Differnce = ' . ($endtime - $starttime) . ' sec<br>';

var_dump($response);

//$response = $api->doesUserExist('brobinson@digitalchalk.com');

//var_dump($response);

$newEmail = 'Automated-' . randomString(10) . '@digitalchalk.com';
$newPassword = randomString(10);

$newUser = array(
		
		'emailAddress' => $newEmail,
		'username' => $newEmail,
		'firstName' => 'Automated',
		'lastName' => 'TestUser',
		'password' => $newPassword,
		'passwordReset' => 'false',
		'licenseAgreed' => 'true',
		'tags' => 'testuser apiv4'		
		
		);

//$response = $api->createUser(array('user' => $newUser));

//var_dump($response);

?>
APIv4 start page