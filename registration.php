<?php
date_default_timezone_set('America/New_York');
//retrieve our data from POST
$username = $_POST['username'];
$password1 = $_POST['password1'];
$password2 = $_POST['password2'];
$email = $_POST['email'];

$hash = hash('sha256', $password1);

function createSalt()
{
    $text = md5(uniqid(rand(), true));
    return substr($text, 0, 3);
}

$conn = mysqli_connect('localhost', 'rzrushco_admin', 'rzr_3541', 'rzrushco_main');

$salt = createSalt();
$password = hash('sha256', $salt . $hash);
 
//sanitize username
$username = mysqli_real_escape_string($conn,$username);

$checkUser = mysqli_query($conn,"SELECT * from member WHERE username = '$username'");
$checkEmail = mysqli_query($conn,"SELECT * from member where email = '$email'");
$result = "";
if (!$checkUser || !$checkEmail) {
    die('Query failed to execute for some reason');
}
if (mysqli_num_rows($checkUser) > 0) {
    echo "User id exists already.";
	$result = "sameuser";
} else if (mysqli_num_rows($checkEmail) > 0) {
	echo "There is already an account with that email address.";
	$result = "sameemail";
} else {
	$signupdate = date("m")."/".date("d")."/".date("y");
	$ipaddress = $_SERVER['REMOTE_ADDR'];
	$ip_result = mysqli_query($conn,"SELECT id FROM member WHERE ipaddress='$ipaddress'");
	if (mysqli_num_rows($ip_result) > 0) {
		$result = "sameip";
		header('Location: register_result.php?result='.$result);
		die();
	}
	$query = "INSERT INTO member ( username, password, email, salt, signup, premium, last_login, ipaddress )
			VALUES ( '$username', '$password', '$email', '$salt', '$signupdate', 'n', '$signupdate','$ipaddress' );";
	mysqli_query($conn, $query);
	if (mysqli_affected_rows($conn) == 1) {
		$result = "success";
	}
	$userid = mysqli_insert_id($conn);
	$friend_query = "INSERT INTO friends (friend_one, friend_two, status) VALUES ($userid, $userid, '2');";
	mysqli_query($conn, $friend_query);
}

mysqli_close($conn);
 
header('Location: register_result.php?result='.$result);
?>