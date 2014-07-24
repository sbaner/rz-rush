<?php


	$conn = mysqli_connect('localhost', 'rzrushco_admin', 'rzr_3541', 'rzrushco_main');
	mysqli_query($conn,"UPDATE minutes SET minute=minute+1");

?>