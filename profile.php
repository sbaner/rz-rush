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
	  if ($profileID == $userID) {
		$own_profile = true;
	  } else {
		$own_profile = false;
	  }
	} else {
		$profileID = $userID;
		$own_profile = true;
	}
	
	$conn = mysqli_connect('mysql7.000webhost.com', 'a6436541_rzr', 'rzr_3541', 'a6436541_login');
	$member_result = mysqli_query($conn,"SELECT * FROM member WHERE `id`='$profileID'");
	$own_team_result = mysqli_query($conn,"SELECT * FROM team WHERE `owner`='$userID'");
	$photo_result = mysqli_query($conn,"SELECT * FROM photos WHERE `member_id`='$profileID' and pri='yes'");
	$profile_team_result = mysqli_query($conn,"SELECT * FROM `team` WHERE owner='$profileID'");
	
	if(mysqli_num_rows($member_result) == 0) { //user not found
		header('Location: 404.php');
	} else {
		$memberData = mysqli_fetch_array($member_result, MYSQL_ASSOC);
		$profile_name = $memberData['username'];
		$profile_email = $memberData['email'];
		$profile_signup = $memberData['signup'];
		$profile_premium = $memberData['premium'];
		$last_login = $memberData['last_login'];
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
      <div class="row" id="top">
        <div class="col-md-2">
          <a href="./index.php">
            <img class="logo" src="./logo-small.png" />
          </a>
        </div>
        <div class="col-md-8">
          <div class="nav">
            <ul class="nav nav-pills navbar-left">
              <li
			  <?php if($own_profile){echo " class=\"active\"";}?>>
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
                <a href="#">Help</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="row" id="content">
        <div class="col-md-2">
          <div class="side-bar">
            <?php 
			if ($own_profile) {
			echo "<h3>Profile Links</h3>
            <div class=\"nav\">
              <ul class=\"nav nav-pills nav-stacked navbar-left\">
                <li class=\"active\">
                  <a href=\"profile.php\">View Profile</a>
                </li>
				<li>
                  <a href=\"editprofile.php\">Edit Profile</a>
                </li>
                <li>
                  <a href=\"#\">Add/Remove Teams</a>
                </li>
                <li>
                  <a href=\"#\">Premium</a>
                </li>
              </ul>
            </div>";
			}
			?>
			<h3>Friends</h3>
			<p>No one :(</p>
          </div>
		  <form class="form-horizontal" id="logout-form" action="logout.php" role="form">
			<button type="submit" class="btn btn-primary">Log out</button>
		</form>
        </div>
        <div class="col-md-8">
			<div class="main">
				<div class="profile-card">
						<div class="row">
							<div class="col-md-3">
								<h3><?php echo $profile_name; ?></h3>
								<p class="premium">Premium Member <span class="glyphicon glyphicon-star"></span> </p>
								<?php
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
									<?php 
									for ($i=0; $i<mysqli_num_rows($profile_team_result); $i++) {
										$profile_teamData = mysqli_fetch_array($profile_team_result, MYSQL_ASSOC);
										$profile_teamid = $profile_teamData['id'];
										$profile_location = $profile_teamData['location'];
										$profile_teamname = $profile_teamData['teamname'];
										$profile_league = $profile_teamData['league'];
										
										echo "<p><a href=\"team.php?teamid=".$profile_teamid."\">".$profile_location." ".$profile_teamname."</a><br>(League ".$profile_league.")</p>";
									}
									?>
								</div>
							</div>
							<div class="col-md-3">
								<div class="third-col">
									<h4>Owner Info</h4>
									<p>Member since: <?php echo $profile_signup;?>
									<p>Total Record: 38-0</p>
									<p>Championships: 2</p>
									<p>Last activity: <?php echo $last_login;?></p>
								</div>
							</div>
							<div class="col-md-3">
								<div class="last-col">
										<div class="row">
											<button type="button" class="btn btn-primary"><span class="glyphicon glyphicon-envelope"></span> Message</button>
										</div>
										<div class="row">
											<button type="button" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Add Friend</button>
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
