<?php

$conn = mysqli_connect('localhost', 'rzrushco_admin', 'rzr_3541', 'rzrushco_main');
$qb_result = mysqli_query($conn,"SELECT * FROM player WHERE position='QB' ORDER BY overall_now DESC");
$qbData = mysqli_fetch_array($qb_result, MYSQL_ASSOC);
echo $qbData['firstname'];
?>