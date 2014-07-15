<?php
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

$conn = mysqli_connect('mysql7.000webhost.com', 'a6436541_rzr', 'rzr_3541', 'a6436541_login');

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
	$query = "INSERT INTO member ( username, password, email, salt )
			VALUES ( '$username', '$password', '$email', '$salt' );";
	mysqli_query($conn, $query);
	$result = "success";

}

mysqli_close($conn);
 
header('Location: register_result.php?result='.$result);
?>