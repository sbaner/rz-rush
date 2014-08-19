<?php
session_start();
date_default_timezone_set('America/New_York');

$username = $_POST['username'];
$password = $_POST['password'];
 
$conn = mysqli_connect('localhost', 'rzrushco_admin', 'rzr_3541', 'rzrushco_main');
 
$username = mysqli_real_escape_string($conn, $username);
$query = "SELECT *
        FROM member
        WHERE username = '$username';";
 
$result = mysqli_query($conn, $query);
 
if(mysqli_num_rows($result) == 0) // User not found. So, redirect to login_form again.
{
    header('Location: index.php?result=fail');
}
 
$userData = mysqli_fetch_array($result, MYSQL_ASSOC);
$hash = hash('sha256', $userData['nordic'] . hash('sha256', $password) );
 
if($hash != $userData['alibaba']) // Incorrect password. So, redirect to login_form again.
{
    header('Location: index.php?result=fail');
} else if ($userData['active']==0){ // User not activated.
	echo $userData['active'];
} else{ // successful login.
	$_SESSION['userID'] = $userData['id'];
	$_SESSION['username'] = $userData['username'];
	$_SESSION['email'] = $userData['email'];
	
	$date = date("m")."/".date("d")."/".date("y");
	mysqli_query($conn, "UPDATE member SET last_login='$date' WHERE username = '$username'");
	
	header('Location: profile.php');
}
?>