<?php
	session_start();
	ob_start();
	if(isset($_SESSION['userID'])) {
		$userID = $_SESSION['userID'];
		$username = $_SESSION['username'];
		$email = $_SESSION['email'];
	} else {
		header('Location: index.php');
	}
	
	if (!empty($_GET['friendid'])) {
	  $friendid = $_GET['friendid'];
	} else {
		header('Location: 404.php');
	}
	
	$conn = mysqli_connect('localhost', 'rzrushco_admin', 'rzr_3541', 'rzrushco_main');
	
	if (isset($_POST['sendrequest'])) {
		$friend_result = mysqli_query($conn,"INSERT INTO `friends` (friend_one, friend_two, status) VALUES ('$userID','$friendid','0')");
		header('Location: profile.php?profileid='.$friendid);
	}
	
	if (isset($_POST['confirm'])) {
		mysqli_query($conn,"UPDATE `friends` SET status='1' WHERE friend_one=$friendid AND friend_two=$userID");
		header('Location: profile.php');
	}
	
	if (isset($_POST['deny'])) {
		mysqli_query($conn,"DELETE FROM `friends` WHERE friend_one=$friendid AND friend_two=$userID");
		header('Location: profile.php');
	}
	
	if (isset($_POST['removefriend'])) {
		mysqli_query($conn,"DELETE FROM `friends` WHERE friend_one=$friendid AND friend_two=$userID OR friend_one=$userID AND friend_two=$friendid");
		header('Location: profile.php?profileid='.$friendid);
	}
	ob_flush();
?>