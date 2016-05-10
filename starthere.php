<?php 

session_start();
require_once('apikeys.php');
require_once('testdata.php');

$pageTitle = 'API Test Suite Start Page';

?>
<html>
<head>
<?php include "commonheader.php"; ?>
</head>
<body>
<b>There are <?php echo (count($TEST_DESC)-1); ?> test descriptors loaded.</b><br>
<a href="v5-driverpage.php?t=1">Start with Test #1 : <?php echo $TEST_DESC[1]['pageTitle']; ?></a>
<br>
<table cellpadding="3">
<tr><th>Test Num</th><th>Name</th><th>Jump Link</th></tr>
<?php 
	foreach($TEST_DESC as $testKey => $testVal) {
		if($testKey > 0) {
?>
<tr><td><?php echo $testKey; ?></td><td><?php echo $testVal['pageTitle'];?></td><td><span style="font-size:smaller"><a href="v5-driverpage.php?t=<?php echo $testKey; ?>" title="NOT RECOMMENDED">Jump to this test</a></span></td></tr>
<?php 
		}
	}
?>
</table>
<?php include "commonfooter.php";?>
</body>
</html>