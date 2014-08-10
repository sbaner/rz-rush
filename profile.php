<?php
	session_start();
	ob_start();
	if(isset($_SESSION['userID'])) {
		$userID = $_SESSION['userID'];
		$username = $_SESSION['username'];
		$email = $_SESSION['email'];
	} else {
		header('Location: index.php');
		die();
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
	
	$conn = mysqli_connect('localhost', 'rzrushco_admin', 'rzr_3541', 'rzrushco_main');
	$member_result = mysqli_query($conn,"SELECT * FROM member WHERE `id`='$profileID'");
	$own_team_result = mysqli_query($conn,"SELECT * FROM team WHERE `owner`='$userID'");
	$photo_result = mysqli_query($conn,"SELECT * FROM photos WHERE `member_id`='$profileID' and pri='yes'");
	$profile_team_result = mysqli_query($conn,"SELECT * FROM `team` WHERE owner='$profileID'");
	
	if(mysqli_num_rows($member_result) == 0) { //user not found
		header('Location: 404.php');
		die();
	} else {
		$memberData = mysqli_fetch_array($member_result, MYSQL_ASSOC);
		$profile_name = $memberData['username'];
		$profile_email = $memberData['email'];
		$profile_signup = $memberData['signup'];
		$profile_premium = $memberData['premium'];
		$last_login = $memberData['last_login'];
		$profileid = $memberData['id'];
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
	<link rel="shortcut icon" href="favicon.ico" />
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.js"></script>
	<script>
	$( document ).ready(function() {
		$('#resultdiv').hide();
		$('#searchbox').on('keydown', function(e){
		
		var value = $(this).val();
		if (value.length > 1) {
				$.ajax({
				  url: 'search.php',
				  type: 'POST',
				  dataType : 'json',
				  data: {'search': value},
				  success: function(data) {
				  $('#resultdiv').html("");
					$.each(data, function(index,value) {
						var userid = value[0];
						var username = value[1];
						var resultstring = "<a href=\"profile.php?profileid="+userid+"\">"+username+"</a><br>";
						$('#resultdiv').delay(300).slideDown(100);
						$('#resultdiv').append(resultstring);
					});
				  },
				  error: function(xhr, desc, err) {
				  }
				}); //end ajax 
			} else {
				$('#resultdiv').slideUp(100);
			}
		});
		
		$('#searchbox').on('blur', function(e){
			$('#resultdiv').delay(500).slideUp(100);
		});
	});
	</script>
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
        <div class="col-md-3 col-sm-7">
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
		<div class="col-md-2">
			<input type="text" id="searchbox" placeholder="Search for a user" class="form-control"/>
			<div id="resultdiv">
			</div>
		</div>
      </div>
      <div class="row" id="content">
        <div class="col-md-3 col-lg-2">
          <div class="side-bar">
            <?php 
			if ($own_profile) {
			echo "<h3>Profile Links</h3>
            <div class=\"nav\">
              <ul class=\"nav nav-pills nav-stacked navbar-left\">
				<li>
				<a href=\"messages.php\">Messages ";
			$newmessage_result = mysqli_query($conn,"SELECT * FROM messages WHERE `read`='0' AND `to`=$userID");
			if (mysqli_num_rows($newmessage_result) != 0) {
				$num_unread = mysqli_num_rows($newmessage_result);
				echo "<span class=\"badge\">".$num_unread."</span>";
			}
			echo "</a>
				</li>
                <li class=\"active\">
                  <a href=\"profile.php\">View Profile</a>
                </li>
				<li>
                  <a href=\"editprofile.php\">Edit Profile</a>
                </li>
                <li>
                  <a href=\"#\">Premium</a>
                </li>
              </ul>
            </div>";
			}
			?>
			<?php
			$myfriends_result = mysqli_query($conn,"SELECT * FROM `friends` WHERE friend_one=$profileid AND status='1' OR friend_two=$profileid AND status='1'");
			$numfriends = mysqli_num_rows($myfriends_result);
			echo "<h3>Friends (".$numfriends.")</h3><div class=\"friendslist\">";
			if (mysqli_num_rows($myfriends_result) == 0) {
			echo "<p>No one :(</p>";
			} else {
				for ($i=0; $i < mysqli_num_rows($myfriends_result); $i++) {
					$friendData = mysqli_fetch_array($myfriends_result);
					$friend_one = $friendData['friend_one'];
					$friend_two = $friendData['friend_two'];
					if($friend_one == $profileid) {
						$friendname_result = mysqli_query($conn,"SELECT username FROM `member` WHERE id=$friend_two");
						$friendnameData = mysqli_fetch_array($friendname_result);
						$friendname = $friendnameData['username'];
						echo "<p><a href=\"profile.php?profileid=".$friend_two."\">".$friendname."</a></p>";
					} else if ($friend_two == $profileid) {
						$friendname_result = mysqli_query($conn,"SELECT username FROM `member` WHERE id=$friend_one");
						$friendnameData = mysqli_fetch_array($friendname_result);
						$friendname = $friendnameData['username'];
						echo "<p><a href=\"profile.php?profileid=".$friend_one."\">".$friendname."</a></p>";
					}
				}
			}
			echo "</div>";
			//Check for requests
			if ($own_profile) {
				$myrequests_result = mysqli_query($conn,"SELECT * FROM `friends` WHERE friend_two=$userID AND status='0'");
				if (mysqli_num_rows($myrequests_result) > 0) {
					echo "<h4>Friend Requests</h4>";
					for ($i = 0; $i < mysqli_num_rows($myrequests_result); $i++) {
						$requestData = mysqli_fetch_array($myrequests_result, MYSQL_ASSOC);
						$requester = $requestData['friend_one'];
						$requestname_result = mysqli_query($conn, "SELECT username FROM `member` WHERE id=$requester");
						$requestnameData = mysqli_fetch_array($requestname_result, MYSQL_ASSOC);
						$requestername = $requestnameData['username'];
						echo "<form method=\"POST\" id=\"confirm\" action=\"addfriend.php?friendid=".$requester."\" role=\"form\">
							<div class=\"row\" id=\"requestrow\">
							  <div class=\"col-md-5\">".$requestername."
							  </div>
							  <div class=\"col-md-3\">
								<button type=\"submit\" name=\"confirm\" class=\"btn btn-primary btn-xs\">Confirm</button>
							  </div>
							  <div class=\"col-md-3\">
								<button type=\"submit\" name=\"deny\" class=\"btn btn-primary btn-xs\">Deny</button>
							  </div>
							 </div>
						</form>";
					}
				}
			}
			?>
          </div>
		  <form class="form-horizontal" id="logout-form" action="logout.php" role="form">
			<button type="submit" class="btn btn-primary">Log out</button>
		</form>
        </div>
        <div class="col-md-9 col-lg-8">
			<div class="main">
				<div class="profile-card">
						<div class="row">
							<div class="col-md-3">
								<h3><?php echo $profile_name; ?></h3>
								<?php if ($profile_premium == "y") { echo
								"<p class=\"premium\">Premium Member <span class=\"glyphicon glyphicon-star\"></span> </p>"; }
								else {
								echo "<div class=\"premiumplaceholder\"></div>";}
								?>
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
									$profile_total_wins = 0;
									$profile_total_loss = 0;
									$profile_total_ties = 0;
									$profile_championships = 0;
									for ($i=0; $i<mysqli_num_rows($profile_team_result); $i++) {
										$profile_teamData = mysqli_fetch_array($profile_team_result, MYSQL_ASSOC);
										$profile_teamid = $profile_teamData['id'];
										$profile_location = $profile_teamData['location'];
										$profile_teamname = $profile_teamData['teamname'];
										$profile_league = $profile_teamData['league'];
										$profile_total_wins = $profile_total_wins + $profile_teamData['total_win'];
										$profile_total_loss = $profile_total_loss + $profile_teamData['total_loss'];
										$profile_total_ties = $profile_total_ties + $profile_teamData['total_tie'];
										$profile_championships = $profile_championships + $profile_teamData['championships'];
										
										$league_result = mysqli_query($conn,"SELECT leaguename FROM league WHERE id=$profile_league");
										$leagueData = mysqli_fetch_array($league_result, MYSQL_ASSOC);
										$leaguename = $leagueData['leaguename'];
										echo "<p><a href=\"team.php?teamid=".$profile_teamid."\">".$profile_location." ".$profile_teamname."</a><br>(".$leaguename.")</p>";
									}
									?>
								</div>
							</div>
							<div class="col-md-3">
								<div class="third-col">
									<h4>Owner Info</h4>
									<p>Member since: <?php echo $profile_signup;?>
									<p>Total Record: <?php echo $profile_total_wins."-".$profile_total_loss;
									if ($profile_total_ties > 0) {
										echo "-".$profile_total_ties;
									}?></p>
									<p>Championships: <?php echo $profile_championships;?></p>
									<p>Last activity: <?php echo $last_login;?></p>
								</div>
							</div>
							<div class="col-md-3">
								<div class="last-col">
									<?php 
									$friendrequest_query = "SELECT * FROM `friends` WHERE friend_one=$userID AND friend_two=$profileid AND status='0'";
									$isfriend_query = "SELECT * FROM `friends` WHERE (friend_one=$userID AND friend_two=$profileid AND status='1') OR (friend_one=$profileid AND friend_two=$userID AND status='1')";
									$friendrequest_result = mysqli_query($conn,$friendrequest_query);
									$isfriend_result = mysqli_query($conn,$isfriend_query);
									if (!$own_profile) {
										echo "<div class=\"row\">
											<form method=\"POST\" id=\"newmessage\" action=\"newmessage.php\" role=\"form\">
												<input type=\"hidden\" name=\"recipient\" value=\"".$profile_name."\">
												<button type=\"submit\" class=\"btn btn-primary\" name=\"newmessage\"><span class=\"glyphicon glyphicon-envelope\"></span> Message</button>
											</form>
											</div>";
										if (mysqli_num_rows($friendrequest_result) == 1) {
											//Friend request sent
											echo "<div class=\"row\">
												<button type=\"button\" class=\"btn btn-primary\" id=\"sendrequest\" disabled><span class=\"glyphicon glyphicon-ok\"></span> Friend Request Sent</button>
											</div>";
										} else if (mysqli_num_rows($isfriend_result) == 1) {
											//Is friend
											echo "<div class=\"row\">
												<form method=\"POST\" id=\"addfriend\" action=\"addfriend.php?friendid=".$memberData['id']."\" role=\"form\">
													<button type=\"submit\" class=\"btn btn-primary\" name=\"removefriend\" id=\"removefriend\" onclick=\"return confirm('Remove ".$profile_name." as a friend?');\"><span class=\"glyphicon glyphicon-remove\"></span> Remove Friend</button>
												</form>
											</div>";
										} else {
											echo "<div class=\"row\">
													<form method=\"POST\" id=\"addfriend\" action=\"addfriend.php?friendid=".$memberData['id']."\" role=\"form\">
														<button type=\"submit\" class=\"btn btn-primary\"  name=\"sendrequest\" id=\"sendrequest\"><span class=\"glyphicon glyphicon-plus\"></span> Add Friend</button>
													</form>
												</div>";
											}
									}
									ob_flush();
									?>
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
