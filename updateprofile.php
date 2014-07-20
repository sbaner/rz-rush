<?php
	session_start();
	if(isset($_SESSION['userID'])) {
		$userID = $_SESSION['userID'];
		$username = $_SESSION['username'];
		$email = $_SESSION['email'];
	} else {
		header('Location: index.php');
	}
	
	//Retrieve POST data
	$newemail = $_POST['newemail'];
	$oldpassword = $_POST['oldpassword'];
	$newpassword = $_POST['password1'];
	$message = "";
	
	$conn = mysqli_connect('localhost', 'rzrushco_admin', 'rzr_3541', 'rzrushco_main');
	
	function createSalt()
		{
			$text = md5(uniqid(rand(), true));
			return substr($text, 0, 3);
		}
	
	//Update email
	if ($newemail != "") {
		$email_result = mysqli_query($conn,"UPDATE member SET email='$newemail' WHERE id=$userID");
		
		if (mysqli_affected_rows($conn) == 1) {
			echo "Email successfully updated";
		} else {
			echo "Something went wrong while updating email.";
		}
	}

	//Update password
	if ($newpassword != "") {
		$pass_result = mysqli_query($conn,"SELECT * FROM member WHERE id=$userID");
		$userData = mysqli_fetch_array($pass_result, MYSQL_ASSOC);
		$oldhash = hash('sha256', $userData['salt'] . hash('sha256', $oldpassword));
		
		if($oldhash != $userData['password']) {
			//Incorrect password
			echo "Password was incorrect";
		} else {
			$newhash = hash('sha256', $newpassword);
			$salt = createSalt();
			$newpassword = hash('sha256', $salt . $newhash);
			
			$update_pass = mysqli_query($conn,"UPDATE member SET password='$newpassword',salt='$salt' WHERE id=$userID");
			if (mysqli_affected_rows($conn) == 1) {
				echo "<br>Password successfully updated";
			} else {
				echo "<br>Something went wrong while updating email";
			}
		}
	}
	
	echo "<br>All done</br>";
?>