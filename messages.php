<?php
	session_start();
	if(isset($_SESSION['userID'])) {
		$userID = $_SESSION['userID'];
		$username = $_SESSION['username'];
		$email = $_SESSION['email'];
	} else {
		header('Location: index.php');
	}
	
	
	$conn = mysqli_connect('localhost', 'rzrushco_admin', 'rzr_3541', 'rzrushco_main');
	$own_team_result = mysqli_query($conn,"SELECT * FROM team WHERE `owner`='$userID'");
	
	if(isset($_POST['delete'])) {
		$messageid = $_POST['messageid'];
		mysqli_query($conn,"DELETE FROM messages WHERE id=$messageid");
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
    <link href="css/messages.css" rel="stylesheet" />
	<link rel="shortcut icon" href="favicon.ico" />
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.js"></script>
    <title>RedZone Rush - Messages</title>
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
			
			echo "<h3>Profile Links</h3>
            <div class=\"nav\">
              <ul class=\"nav nav-pills nav-stacked navbar-left\">
				<li class=\"active\">
				<a href=\"messages.php\">Messages ";
			$newmessage_result = mysqli_query($conn,"SELECT * FROM messages WHERE `read`='0' AND `to`=$userID ORDER BY timestamp DESC");
			$oldmessage_result = mysqli_query($conn,"SELECT * FROM messages WHERE `read`='1' AND `to`=$userID ORDER BY timestamp DESC");
			if (mysqli_num_rows($newmessage_result) != 0) {
				$num_unread = mysqli_num_rows($newmessage_result);
				echo "<span class=\"badge\">".$num_unread."</span>";
			}
			echo "</a>
				</li>
                <li>
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
			
			?>
			<?php
			$myfriends_result = mysqli_query($conn,"SELECT * FROM `friends` WHERE friend_one=$userID AND status='1' OR friend_two=$userID AND status='1'");
			$numfriends = mysqli_num_rows($myfriends_result);
			echo "<h3>Friends (".$numfriends.")</h3><div class=\"friendslist\">";
			if (mysqli_num_rows($myfriends_result) == 0) {
			echo "<p>No one :(</p>";
			} else {
				for ($i=0; $i < mysqli_num_rows($myfriends_result); $i++) {
					$friendData = mysqli_fetch_array($myfriends_result);
					$friend_one = $friendData['friend_one'];
					$friend_two = $friendData['friend_two'];
					if($friend_one == $userID) {
						$friendname_result = mysqli_query($conn,"SELECT username FROM `member` WHERE id=$friend_two");
						$friendnameData = mysqli_fetch_array($friendname_result);
						$friendname = $friendnameData['username'];
						echo "<p><a href=\"profile.php?profileid=".$friend_two."\">".$friendname."</a></p>";
					} else if ($friend_two == $userID) {
						$friendname_result = mysqli_query($conn,"SELECT username FROM `member` WHERE id=$friend_one");
						$friendnameData = mysqli_fetch_array($friendname_result);
						$friendname = $friendnameData['username'];
						echo "<p><a href=\"profile.php?profileid=".$friend_one."\">".$friendname."</a></p>";
					}
				}
			}
			echo "</div>";
			//Check for requests
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
			
			?>
          </div>
		  <form class="form-horizontal" id="logout-form" action="logout.php" role="form">
			<button type="submit" class="btn btn-primary">Log out</button>
		</form>
        </div>
        <div class="col-md-6 col-md-offset-1">
			<div class="main">
				<h3>Messages</h3>
				<?php
				if (mysqli_num_rows($newmessage_result) != 0) {
					for ($i=0;$i<mysqli_num_rows($newmessage_result);$i++) {
						$messageData = mysqli_fetch_array($newmessage_result);
						$messageid = $messageData['id'];
						$sender = $messageData['from'];
						$subject = $messageData['subject'];
						$message = $messageData['message'];
						$timestamp = $messageData['timestamp'];
						
						$sender_result = mysqli_query($conn,"SELECT username FROM member WHERE id=$sender");
						$senderData = mysqli_fetch_array($sender_result);
						$sender_name = $senderData['username'];
						
						$photo_result = mysqli_query($conn,"SELECT filename FROM photos WHERE member_id=$sender AND pri='yes'");
						$photoData = mysqli_fetch_array($photo_result);
						if ($photoData['filename'] != "") {
							$imagepath = "./uploads/".$photoData['filename'];
						} else {
							$imagepath="profile.jpg";
						}
						
						echo "<div class=\"container\">
					<div class=\"row\">
						<div class=\"col-md-5\">
							<div class=\"well newmessage\">
								<div class=\"row\" id=\"sender\">
									<div class=\"col-md-3\">
										<img src=\"".$imagepath."\" height=\"50\">
										<strong>".$sender_name."</strong>
									</div>
									<div class=\"col-md-8\">
									<strong><a href=\"profile.php?profileid=".$sender."\">".$sender_name."</a></strong>
									Time sent: ".$timestamp."
									</div>
								</div>
								<div class=\"row\" id=\"message\">
									<div class=\"col-md-8 col-md-offset-3\">
									".$message."
									</div>
								</div>
								<div class=\"row\">
									<form method=\"POST\" action=\"messages.php\" role=\"form\">
										<input type=\"hidden\" name=\"messageid\" value=\"".$messageid."\">
										<button type=\"submit\" id=\"delete\" name=\"delete\" class=\"btn btn-primary\"><span class=\"glyphicon glyphicon-trash\"></span> Delete</button>
									</form>
									<form method=\"POST\" action=\"newmessage.php\" role=\"form\">
										<input type=\"hidden\" name=\"convsubject\" value=\"".$subject."\">
										<input type=\"hidden\" name=\"sender\" value=\"".$sender_name."\">
										<button type=\"submit\" id=\"reply\" name=\"reply\" class=\"btn btn-primary\"><span class=\"glyphicon glyphicon-share-alt\"></span> Reply</button>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>";
					}
					mysqli_query($conn,"UPDATE messages SET `read`='1' WHERE id=$messageid");
				}
				for ($i=0;$i<mysqli_num_rows($oldmessage_result);$i++) {
						$messageData = mysqli_fetch_array($oldmessage_result);
						$messageid = $messageData['id'];
						$sender = $messageData['from'];
						$subject = $messageData['subject'];
						$message = $messageData['message'];
						$timestamp = $messageData['timestamp'];
						
						$sender_result = mysqli_query($conn,"SELECT username FROM member WHERE id=$sender");
						$senderData = mysqli_fetch_array($sender_result);
						$sender_name = $senderData['username'];
						
						$photo_result = mysqli_query($conn,"SELECT filename FROM photos WHERE member_id=$sender AND pri='yes'");
						$photoData = mysqli_fetch_array($photo_result);
						if ($photoData['filename'] != "") {
							$imagepath = "./uploads/".$photoData['filename'];
						} else {
							$imagepath="profile.jpg";
						}
						
						echo "<div class=\"container\">
					<div class=\"row\">
						<div class=\"col-md-5\">
							<div class=\"well\">
								<div class=\"row\" id=\"sender\">
									<div class=\"col-md-3\">
										<img src=\"".$imagepath."\" height=\"50\">
										<strong><a href=\"profile.php?profileid=".$sender."\">".$sender_name."</a></strong>
									</div>
									<div class=\"col-md-8\">
									<strong>".$subject."</strong><br>
									Time sent: ".$timestamp."
									</div>
								</div>
								<div class=\"row\" id=\"message\">
									<div class=\"col-md-8 col-md-offset-3\">
									".$message."
									</div>
								</div>
								<div class=\"row\">
									<form method=\"POST\" action=\"messages.php\" role=\"form\">
										<input type=\"hidden\" name=\"messageid\" value=\"".$messageid."\">
										<button type=\"submit\" id=\"delete\" name=\"delete\" class=\"btn btn-primary\"><span class=\"glyphicon glyphicon-trash\"></span> Delete</button>
									</form>
									<form method=\"POST\" action=\"newmessage.php\" role=\"form\">
										<input type=\"hidden\" name=\"convsubject\" value=\"".$subject."\">
										<input type=\"hidden\" name=\"sender\" value=\"".$sender_name."\">
										<button type=\"submit\" id=\"reply\" name=\"reply\" class=\"btn btn-primary\"><span class=\"glyphicon glyphicon-share-alt\"></span> Reply</button>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>";
					}
				if (mysqli_num_rows($newmessage_result) == 0 && mysqli_num_rows($oldmessage_result) == 0) {
					echo "You have no messages!";
				}
				?>
			</div>
        </div>
      </div>
    </div>
  </body>
</html>
