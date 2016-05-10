<?php 

session_start();
require_once('apiv5.class.php');
require_once('apikeys.php');
require_once('testfunctions.php');
require_once('testdata.php');

if(empty($_GET['t'])) {
	die ('No test was passed in');
}
$testNum = $_GET['t'];
if(!empty($TEST_DESC[$testNum])) {
	$pageTitle = $TEST_DESC[$testNum]['pageTitle'];
	$pageLink = $TEST_DESC[$testNum]['pageLink'];
}
if(!empty($TEST_DESC[($testNum + 1)])) {
	$nextTitle = $TEST_DESC[$testNum + 1]['pageTitle'];
	$nextPageLink = $TEST_DESC[$testNum + 1]['pageLink'];
}

include_once 'v5tests/' . $pageLink;

if(!empty($refData)) {
	addRefData($refData);
}

$testSuccessful = (!empty($result) && !empty($result['api_result']) && $result['api_result'] == 'success');
if($expectFailure && !empty($result) && empty($testErrorMsg) && $result['http_status_code'] > 0) {
	$testSuccessful = !$testSuccessful;
}

?>
<html>
<head>
<?php include "commonheader.php"; ?>
</head>
<body>
<h1><?php echo 'Test #' . $testNum . ': ' . $pageTitle; ?></h1>
<h3><?php 
if(!empty($result)) {
	echo 'TEST Result: ' . ($testSuccessful ? 'PASSED' : 'FAILED') . '<br>';
	if($expectFailure) {
		echo '(API Failure was expected in this test)<br>';
	}
} else {
	echo 'Empty API Result. Fail.<br>';
}
?></h3>
<?php 
	if(!empty($testErrorMsg)) {
		echo '<h3 style="color:red">' . $testErrorMsg . '</h3>';
?>
		<a href="v5-driverpage.php?t=<?php echo ($testNum) ?>">ReRun This Test #<?php echo $testNum; ?>: <?php echo $pageTitle; ?></a><br/>
<?php 
		if(!empty($nextTitle)) {
?>
<a href="v5-driverpage.php?t=<?php echo ($testNum+1) ?>">Proceed anyway (NOT RECOMMENDED) to Test: <?php echo '#' . ($testNum+1) . ': ' . $nextTitle; ?></a><br/>
<?php 			
		}
		echo '<b>SESSION</b><br>';
		var_dump($_SESSION);
		echo '<br>';
		
	}
?>
<?php 
if(empty($testErrorMsg)) {
	if($testSuccessful) {
		if(!empty($nextTitle)) {
?>
<a href="v5-driverpage.php?t=<?php echo ($testNum+1) ?>">Next Test: <?php echo '#' . ($testNum+1) . ': ' . $nextTitle; ?></a><br/>
<?php 
		} else {
			echo '<b>Test passed, but no next test was provided.  I don\'t know where to go next</b><br>';
		}
	}
}
?>
<?php 
	if(!empty($refData)) {
		echo '<b>Test Reference Data Added</b><br>';
		var_dump($refData);
	}
	if(!empty($postData)) {
		echo '<b>Data posted to API</b><br>';
		var_dump($postData);
	}
?>
<?php if(!empty($result)) {echo '<b>Result Data</b><br>'; var_dump($result);} ?>
<?php include "commonfooter.php";?>
</body>
</html>