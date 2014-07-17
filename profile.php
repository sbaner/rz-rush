<?php
	session_start();
	if(isset($_SESSION['userID'])) {
		$userID = $_SESSION['userID'];
		$username = $_SESSION['username'];
		$email = $_SESSION['email'];
	} else {
		header('Location: index.php');
	}
	if (!empty($_GET['profileid'])) {
	  $profileID = $_GET['profileid'];
	} else {
		$profileID = $userID;
	}
	
	$conn = mysqli_connect('mysql7.000webhost.com', 'a6436541_rzr', 'rzr_3541', 'a6436541_login');
	$member_result = mysqli_query($conn,"SELECT * FROM member WHERE `id`='$profileID'");
	if(mysqli_num_rows($member_result) == 0) { //user not found
		header('Location: 404.php');
	} else {
		$memberData = mysqli_fetch_array($member_result, MYSQL_ASSOC);
		$profile_name = $memberData['username'];
		$profile_email = $memberData['email'];
		$profile_signup = $memberData['signup'];
	}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="generator"
    content="HTML Tidy for HTML5 (experimental) for Windows https://github.com/w3c/tidy-html5/tree/c63cc39" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link href="css/bootstrap.css" rel="stylesheet" />
    <link href="css/main.css" rel="stylesheet" />
    <link href="css/profile.css" rel="stylesheet" />
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.js"></script>
    <title>RedZone Rush - <?php echo $username;?></title>
  </head>
  <body>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-2">
          <a href="./index.php">
            <img class="logo" src="./logo-small.png" />
          </a>
        </div>
        <div class="col-md-8">
          <div class="nav">
            <ul class="nav nav-pills navbar-left">
              <li class="active">
                <a href="profile.php">Profile</a>
              </li>
              <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">League <span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li role="presentation"><a role="menuitem" tabindex="-1" href="league.php">League 1</a></li>
						<li role="presentation" class="divider"></li>
						<li role="presentation"><a role="menuitem" tabindex="-1" href="league.php">League 2</a></li>
					</ul>
              </li>
              <li>
                <a href="team.php">Team</a>
              </li>
              
              <li>
                <a href="#">Help</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-2">
          <div class="side-bar">
            <div class="team-card">
            <h3>My teams</h3>
            <p><a href="team.php">
              New York Giants
            </a><br>(League 1)</p><p><a href="team.php">
              New York Empire
            </a><br>(League 2)</p>
            <h3>Profile Links</h3></div>
            <div class="nav">
              <ul class="nav nav-pills nav-stacked navbar-left">
                <li class="active">
                  <a href="profile.php">View Profile</a>
                </li>
				<li>
                  <a href="editprofile.php">Edit Profile</a>
                </li>
                <li>
                  <a href="#">Add/Remove Teams</a>
                </li>
                <li>
                  <a href="#">Premium</a>
                </li>
              </ul>
            </div>
			<h3>Your Friends</h3>
			<p>No one :(</p>
          </div>
		  <form class="form-horizontal" id="logout-form" action="logout.php" role="form">
			<button type="submit" class="btn btn-default">Log out</button>
		</form>
        </div>
        <div class="col-md-8">
			<div class="main">
				<div class="profile-card">
					<div class="container">
						<div class="row">
							<div class="col-md-3">
								<h3><?php echo $profile_name; ?></h3>
								<p class="premium">Premium Member <span class="glyphicon glyphicon-star"></span> </p>
								<?php
									$photo_result = mysqli_query($conn,"SELECT * FROM photos WHERE `member_id`='$profileID' and pri='yes'");
									if(mysqli_num_rows($photo_result) == 0) {
										//no prof pic uploaded
										echo "<img src=\"profile.jpg\">";
									} else {
										$photoData = mysqli_fetch_array($photo_result, MYSQL_ASSOC);
										$imagepath = "./uploads/".$photoData['filename'];
										echo "<img src=\"$imagepath\">";
									}
								?>
							</div>
							<div class="col-md-3">
								<div class="middle-col">
									<h4>Teams</h4>
									<p><a href="team.php">
										New York Giants
										</a><br>(League 1)</p><p>
										<a href="team.php">
										New York Empire
										</a><br>(League 2)</p>
								</div>
							</div>
							<div class="col-md-3">
								<div class="third-col">
									<h4>Owner Info</h4>
									<p>Member since: <?php echo $profile_signup;?>
									<p>Total Record: 38-0</p>
									<p>Championships: 2</p>
									<p>Last activity: 6/30/2014</p>
								</div>
							</div>
							<div class="col-md-3">
								<div class="last-col">
									<div class="container">
										<div class="row">
											<button type="button" class="btn btn-default"><span class="glyphicon glyphicon-envelope"></span> Message</button>
										</div>
										<div class="row">
											<button type="button" class="btn btn-default"><span class="glyphicon glyphicon-plus"></span> Add Friend</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
        </div>
      </div>
    </div>
  </body>
</html>
