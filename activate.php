<?php
if (isset($_GET['uid'])&&isset($_GET['confirmcode'])) {
	$userid = $_GET['uid'];
	$confirmcode = $_GET['confirmcode'];
	$conn = mysqli_connect('localhost', 'rzrushco_admin', 'rzr_3541', 'rzrushco_main');
	
	$member_result = mysqli_query($conn,"SELECT active,confirmation FROM member WHERE id=$userid");
	$memberData = mysqli_fetch_array($member_result);
	
	if ($memberData['active']==0) {
		if ($memberData['confirmation']==$confirmcode) {
			mysqli_query($conn,"UPDATE member SET active='1'");
			header('Location: register_result.php?result=activated');
		} else { //incorrect confirmation code
			header('Location: index.php');
		}
	} else if ($memberData['active']==1){ //user is already active
		header('Location: register_result.php?result=activated');
	} else {
		header('Location: index.php');
	}
} else {
	header('Location: index.php');
}
?>