<?php
	$conn = mysqli_connect('localhost', 'rzrushco_admin', 'rzr_3541', 'rzrushco_main');
	
	
		$assignedweeks = [];
		$week_result = mysqli_query($conn,"SELECT week FROM `games` WHERE week IS NOT NULL AND (home=1 OR away=1)");
		while ($weekData = mysqli_fetch_array($week_result)) {
			$assignedweeks[] = $weekData['week'];
		}
	
	if (in_array(3,$assignedweeks)) {
		echo "3 is in array";
	}
?>