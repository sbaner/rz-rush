<?php
session_start();

$username = $_POST['username'];
$password = $_POST['password'];
 
$conn = mysqli_connect('mysql7.000webhost.com', 'a6436541_rzr', 'rzr_3541', 'a6436541_login');
 
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
$hash = hash('sha256', $userData['salt'] . hash('sha256', $password) );
 
if($hash != $userData['password']) // Incorrect password. So, redirect to login_form again.
{
    header('Location: index.php?result=fail');
}else{ // successful login.
	$_SESSION['userID'] = $userData['id'];
	$_SESSION['username'] = $userData['username'];
	$_SESSION['email'] = $userData['email'];
	
	header('Location: profile.php');
}
?>