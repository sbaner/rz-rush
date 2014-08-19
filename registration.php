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
	
	$confirmcode = md5(microtime());
	$query = "INSERT INTO member ( username, alibaba, email, nordic, signup, premium, last_login, ipaddress, active,confirmation)
			VALUES ( '$username', '$password', '$email', '$salt', '$signupdate', 'y', '$signupdate','$ipaddress','0','$confirmcode');";
	mysqli_query($conn, $query);
	if (mysqli_affected_rows($conn) == 1) {
		$result = "success";
	} else {
		printf("Error: %s\n", mysqli_error($conn));
				exit();
		echo $query;
	}
	
	//Friends
	$userid = mysqli_insert_id($conn);
	$friend_query = "INSERT INTO friends (friend_one, friend_two, status) VALUES ($userid, $userid, '2');";
	mysqli_query($conn, $friend_query);
	
	//Tutorial
	mysqli_query($conn,"INSERT INTO tutorial (member) VALUES ($userid)");
	
	$emailto = $email;
	$toname = $username;
	$emailfrom = 'admin@rzrush.com';
	$fromname = 'RedZone Rush';
	$subject = 'Welcome to RedZone Rush!';
	$messagebody = "
	<html>
		<img src='http://rzrush.com/logo-small.png' style='padding-bottom:20px;'>
		<br>
		Thanks for joining RedZone Rush! Click the activation link below to activate your account: 
		<br><a href='http://rzrush.com/activate.php?uid=".$userid."&confirmcode=".$confirmcode."' style='padding-top:20px;padding-bottom:20px;'>http://rzrush.com/activate.php?uid=".$userid."&confirmcode=".$confirmcode."</a>
		<br>Your username: ".$username."<br>
		<b>Some things you'll want to do to get started: </b>
		<ul>
			<li><b>Upload a profile picture</b></li>
			<li><b>Claim a team</b>: Just click the “Get a Team” link at the top, pick a league, and pick up an unowned team. Congrats! You now own your very own football team.</li>
			<li><b>Activate your players and set your lineup</b>: Select your active roster of 46 players, and put who you think is best in your lineup!</li>
		</ul>
		<br>
		Enjoy, and check the documentation if you have any questions!
	</html>
	";
	$headers = 
		'Return-Path: ' . $emailfrom . "\r\n" . 
		'From: ' . $fromname . ' <' . $emailfrom . '>' . "\r\n" . 
		'X-Priority: 3' . "\r\n" . 
		'X-Mailer: PHP ' . phpversion() .  "\r\n" . 
		'Reply-To: ' . $fromname . ' <' . $emailfrom . '>' . "\r\n" .
		'MIME-Version: 1.0' . "\r\n" . 
		'Content-Transfer-Encoding: 8bit' . "\r\n" . 
		'Content-Type: text/html; charset=iso-8859-1' . "\r\n";
	$params = '-f ' . $emailfrom;
	$test = mail($emailto, $subject, $messagebody, $headers, $params);
	// $test should be TRUE if the mail function is called correctly
	if (!$test) {
		die("Email didn't send");
	}
}

mysqli_close($conn);
 
header('Location: register_result.php?result='.$result);
?>