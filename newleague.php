<?php
	session_start();
	if(isset($_SESSION['userID'])) {
		$userID = $_SESSION['userID'];
		$username = $_SESSION['username'];
		$email = $_SESSION['email'];
	} else {
		header('Location: index.php');
	}
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
    <link href="../css/register.css" rel="stylesheet" />
    <script src="../js/jquery-1.11.1.min.js"></script>
    <script src="../js/bootstrap.js"></script>
    <title>RedZone Rush - Create League</title>
  </head>
  <body>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-2">
          <a href="../index.php">
            <img class="logo" src="../logo-small.png" />
          </a>
        </div>
        <div class="col-md-10">
          <div class="nav">
            <ul class="nav nav-pills navbar-left">
              <li>
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
                <li>
                  <a href="profile.php">View Profile</a>
                </li>
				<li class="active">
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
        <div class="col-md-offset-1 col-md-6">
          <div class="main">
		  <h3>Edit Profile</h3>
            <form class="form-horizontal" method="POST" id="new-league" action="leaguecreate.php" role="form">
              <div class="form-group">
                <label for="league-name" class="col-sm-2 control-label">League Name</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="league-name" name="league-name" placeholder="League Name">
                </div>
              </div>
			  <div class="form-group">
                <label for="frequency" class="col-sm-2 control-label">Frequency</label>
                <div class="col-sm-10">
				<select class="form-control" id="frequency" name="frequency">
					<option>Every day</option>
					<option>Every other day</option>
					<option>Saturday/Monday/Wednesday</option>
				</select>
                </div>
              </div>
              <div class="form-group">
                <label for="salarycap" class="col-sm-2 control-label">Salary Cap</label>
                <div class="col-sm-10">
				<select class="form-control" id="salarycap" name="salarycap">
					<option>On</option>
					<option>Off</option>
				</select>
                </div>
              </div>
			  <div class="form-group">
                <label for="injuries" class="col-sm-2 control-label">Salary Cap</label>
                <div class="col-sm-10">
				<select class="form-control" id="injuries" name="injuries">
					<option>On</option>
					<option>Off</option>
				</select>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" class="btn btn-default" id="submit" name="submit">Create League</button>
                </div>
              </div>
            </form>
			
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
