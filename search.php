<?php

if (isset($_POST['search'])) {
	
	$search = $_POST['search'];
	$conn = mysqli_connect('localhost', 'rzrushco_admin', 'rzr_3541', 'rzrushco_main');
	$member_result = mysqli_query($conn,"SELECT id,username FROM `member` WHERE username LIKE '$search%' AND id!=0 LIMIT 0,5");
	$result_array = [];
	
	while ($memberData = mysqli_fetch_array($member_result)) {
		$result_array[] = [$memberData['id'],$memberData['username']];
	}
	
	echo json_encode($result_array);
	exit();
} else {
	exit();
}
?>