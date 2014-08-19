<?php
	session_start();
	if(isset($_SESSION['userID'])) {
		$userID = $_SESSION['userID'];
		$username = $_SESSION['username'];
		$email = $_SESSION['email'];
	} else {
		header('Location: index.php');
	}
	
	function createSalt()
		{
			$text = md5(uniqid(rand(), true));
			return substr($text, 0, 3);
		}
	
	$conn = mysqli_connect('localhost', 'rzrushco_admin', 'rzr_3541', 'rzrushco_main');
	
	//Retrieve POST data
	if (isset($_POST['newemail'])) {
		$newemail = $_POST['newemail'];
		if ($newemail != "") {
		$email_result = mysqli_query($conn,"UPDATE member SET email='$newemail' WHERE id=$userID");
		
		if (mysqli_affected_rows($conn) == 1) {
			$udt_message =  "Email successfully updated";
		} else {
			$udt_message = "Something went wrong while updating email.";
		}
	}
	}
	if (isset($_POST['oldpassword'])) {
		$oldpassword = $_POST['oldpassword'];
	}
	if (isset($_POST['password1'])) {
		$newpassword = $_POST['password1'];
		if ($newpassword != "") {
		$pass_result = mysqli_query($conn,"SELECT * FROM member WHERE id=$userID");
		$userData = mysqli_fetch_array($pass_result, MYSQL_ASSOC);
		$oldhash = hash('sha256', $userData['salt'] . hash('sha256', $oldpassword));
		
		if($oldhash != $userData['password']) {
			//Incorrect password
			$udt_message = $udt_message."<br>Password was incorrect";
		} else {
			$newhash = hash('sha256', $newpassword);
			$salt = createSalt();
			$newpassword = hash('sha256', $salt . $newhash);
			
			$update_pass = mysqli_query($conn,"UPDATE member SET password='$newpassword',salt='$salt' WHERE id=$userID");
			if (mysqli_affected_rows($conn) == 1) {
				$udt_message = $udt_message."<br>Password successfully updated";
			} else {
				$udt_messsage = $udt_message."<br>Something went wrong while updating email";
			}
		}
	}
	}
	
	if (isset($_POST['msgcheck'])) { // Send emails on new message
		mysqli_query($conn,"UPDATE member SET msgmail='1' WHERE id=$userID");
	} else { //Do not send emails on new message
		mysqli_query($conn,"UPDATE member SET msgmail='0' WHERE id=$userID");
	}
	
	if (isset($_POST['tradecheck'])) { // Send email on new trade
		mysqli_query($conn,"UPDATE member SET trademail='1' WHERE id=$userID");
	} else { //Do not send emails on new trade
		mysqli_query($conn,"UPDATE member SET trademail='0' WHERE id=$userID");
	}
	$udt_message = "";
	
	
	$own_team_result = mysqli_query($conn,"SELECT * FROM team WHERE `owner`='$userID'");
	
	$check_result = mysqli_query($conn,"SELECT msgmail,trademail FROM member WHERE id=$userID");
	$checkData = mysqli_fetch_array($check_result);
	$msgmail = $checkData['msgmail'];
	$trademail = $checkData['trademail'];
?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="generator"
    content="HTML Tidy for HTML5 (experimental) for Windows https://github.com/w3c/tidy-html5/tree/c63cc39" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link href="../css/bootstrap.css" rel="stylesheet" />
    <link href="../css/main.css" rel="stylesheet" />
    <link href="../css/editprofile.css" rel="stylesheet" />
	<link rel="shortcut icon" href="favicon.ico" />
    <script src="../js/jquery-1.11.1.min.js"></script>
    <script src="../js/bootstrap.js"></script>
	<script>
		function checkForm() {
			var newemail = $("#newemail").val()
			var password = $("#password1").val();
			var confirmPassword = $("#password2").val();
			var button = document.getElementById('signup-button');
			var emailOk = false;
			var passwordOk = false;
			
			if (newemail.length == 0) {
				emailOk = true;
			} else if (newemail.indexOf("@") > 0) {
				emailOk = true;
			} else {
				emailOk = false;
				$("#formcheck").html("Invalid email.");
			}
			
			if (password == confirmPassword) {
				if (password.length > 5) {
					passwordOk = true;
				} else if (password.length == 0) {
					passwordOk = true;
				} else {
					passwordOk = false;
					$("#formcheck").html("New password must be at least 6 characters.");
				}
			} else {
				passwordOk = false;
				$("#formcheck").html("New passwords do not match.");
			}
			
			if (passwordOk && emailOk) {
				button.disabled = false;
				$("#formcheck").html("");
			} else {
				button.disabled = true;
			}
		
		}
		
		$(document).ready(function () {
		   $("#newemail").keyup(checkForm);
		   $("#oldpassword").keyup(checkForm);
		   $("#password1").keyup(checkForm);
		   $("#password2").keyup(checkForm);
		});
		</script>
    <title>RedZone Rush - Edit Profile</title>
  </head>
  <body>
    <div class="container-fluid">
      <div class="row" id="top">
        <div class="col-md-2">
          <a href="../index.php">
            <img class="logo" src="../logo-small.png" />
          </a>
        </div>
        <div class="col-md-10">
          <div class="nav">
            <ul class="nav nav-pills navbar-left">
              <li class="active">
                <a href="profile.php">Profile</a>
              </li>
              <?php
			  $teamidArray = array();
			  $locationArray = array();
			  $teamnameArray = array();
			  $leagueArray = array();
			  
			  //Still gets league data, though league link was removed
			  
				if(mysqli_num_rows($own_team_result) == 0) {
				} else if (mysqli_num_rows($own_team_result) == 1) {
					$own_teamData = mysqli_fetch_array($own_team_result, MYSQL_ASSOC);
					array_push($teamidArray, $own_teamData['id']);
					array_push($locationArray, $own_teamData['location']);
					array_push($teamnameArray, $own_teamData['teamname']);
					array_push($leagueArray, $own_teamData['league']);
				} else if (mysqli_num_rows($own_team_result) > 1) {
					for ($i=1; $i < mysqli_num_rows($own_team_result); $i++) {
						$k = $i - 1;
						$own_teamData = mysqli_fetch_array($own_team_result, MYSQL_ASSOC);
						array_push($teamidArray, $own_teamData['id']);
						array_push($locationArray, $own_teamData['location']);
						array_push($teamnameArray, $own_teamData['teamname']);
						array_push($leagueArray, $own_teamData['league']);
					}
					$own_teamData = mysqli_fetch_array($own_team_result, MYSQL_ASSOC);
					array_push($teamidArray, $own_teamData['id']);
					array_push($locationArray, $own_teamData['location']);
					array_push($teamnameArray, $own_teamData['teamname']);
					array_push($leagueArray, $own_teamData['league']);
				}
			if(mysqli_num_rows($own_team_result) == 0) { 
					//person doesn't own a team
					echo "<li><a href=\"teamselect.php\">Get a Team</a></li>";
				} else if (mysqli_num_rows($own_team_result) == 1) {
					echo "<li><a href=\"team.php?teamid=".$teamidArray[0]."\">Team</a></li>";
				} else if (mysqli_num_rows($own_team_result) > 1) {
					echo "<li class=\"dropdown\">
							<a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\">Team <span class=\"caret\"></span></a>
								<ul class=\"dropdown-menu\" role=\"menu\">";
					for ($i=1; $i < mysqli_num_rows($own_team_result); $i++) {
						$k = $i - 1;
						echo "<li role=\"presentation\"><a role=\"menuitem\" tabindex=\"-1\" href=\"team.php?teamid=".$teamidArray[$k]."\">".$locationArray[$k]." ".$teamnameArray[$k]."</a></li>
						<li role=\"presentation\" class=\"divider\"></li>";
					}
					echo "<li role=\"presentation\"><a role=\"menuitem\" tabindex=\"-1\" href=\"team.php?teamid=".$teamidArray[count($teamidArray)-1]."\">".$locationArray[count($locationArray)-1]." ".$teamnameArray[count($teamnameArray)-1]."</a></li>";
					echo "</ul></li>";
				}
			  ?>
			  <li>
				<a href="allusers.php">Users</a>
			  </li>
              <li>
                <a href="/help" target="_blank">Help</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="row" id="content">
        <div class="col-sm-3 col-lg-2">
          <div class="side-bar">
            <h3>Profile Links</h3>
            <div class="nav">
              <ul class="nav nav-pills nav-stacked navbar-left">
                <li>
                  <a href="profile.php">View Profile</a>
                </li>
				<li class="active">
                  <a href="editprofile.php">Edit Profile</a>
                </li>
                <li>
                  <a href="#">Premium</a>
                </li>
              </ul>
            </div>
          </div>
		  <form class="form-horizontal" id="logout-form" action="logout.php" role="form">
			<button type="submit" class="btn btn-primary">Log out</button>
		</form>
        </div>
        <div class="col-sm-offset-1 col-sm-8 col-lg-6">
          <div class="main">
		  <h3>Edit Profile</h3>
            <form class="form-horizontal" method="POST" id="edit-profile" action="editprofile.php" role="form" autocomplete="off" style="margin-bottom: 30px;">
			<input style="display:none" type="text" name="fakeusernameremembered"/>
			<input style="display:none" type="password" name="fakepasswordremembered"/>
			  <div class="form-group">
                <label for="newemail" class="col-sm-2 control-label">New Email</label>
                <div class="col-sm-10">
                  <input type="email" class="form-control" id="newemail" name="newemail" placeholder="New Email"/>
                </div>
              </div>
			  <div class="form-group">
                <label for="oldpassword" class="col-sm-2 control-label">Current Password</label>
                <div class="col-sm-10">
                  <input type="password" class="form-control" id="oldpassword" name="oldpassword" placeholder="Password"/>
                </div>
              </div>
              <div class="form-group">
                <label for="password1" class="col-sm-2 control-label">New Password</label>
                <div class="col-sm-10">
                  <input type="password" class="form-control" id="password1" name="password1" placeholder="Password"/>
                </div>
              </div>
			  <div class="form-group">
                <label for="password2" class="col-sm-2 control-label">Confirm Password</label>
                <div class="col-sm-10">
                  <input type="password" class="form-control" id="password2" name="password2" placeholder="Password"/>
                </div>
              </div>
			  <div class="checkbox col-sm-offset-2">
				  <label>
					<input type="checkbox" name="msgcheck" value="msgcheck"<?php if ($msgmail==1) { echo " checked"; } ?>>
					Email me when I receive a message
				  </label>
			  </div>
			  <div class="checkbox col-sm-offset-2">
				  <label>
					<input type="checkbox" name="tradecheck" value="tradecheck"<?php if ($trademail==1) { echo " checked"; } ?>>
					Email me when I receive a trade offer for one of my teams
				  </label>
			  </div>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" class="btn btn-primary" id="signup-button">Update Info</button>
                </div>
              </div>
			  <div id="formcheck"><?php echo $udt_message;?></div>
            </form>
			<h4><b>Update Profile Picture</b></h4>
			<?php if(!empty($message)) { echo "<p>{$message}</p>";}?>
			<form action="upload.php" id="profile-pic-form" enctype="multipart/form-data" method="POST">
				
				<input type="hidden" name="MAX_FILE_SIZE" value="5000000">
				<input type="file" id="upload_file" name="upload_file">
                <button type="submit" name="changeprofpic" class="btn btn-primary" id="signup-button">Upload</button>
				<span class="help-block">Image requirements: JPG or PNG, less than 5MB.</span>
			</form>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
