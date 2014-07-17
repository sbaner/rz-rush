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
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link href="css/bootstrap.css" rel="stylesheet" />
    <link href="css/main.css" rel="stylesheet" />
    <link href="css/team.css" rel="stylesheet" />
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.js"></script>
    <title>RedZone Rush - Team</title>
  </head>
  <body>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-2">
          <a href="./index.php">
            <img class="logo" src="./logo-small.png" />
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
              <li class="active">
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
            <h3>My team</h3>
            <a href="team.php">
              <img src="nfl-logos/19.png" />
            </a> 
            <a href="team.php">
              <p>New York Giants</p>
            </a>
			<p>Week 1</p>
            <p>Next game: @<a href="#">DAL</a></p>
			<p><a href="league.php">League X</a></p>
            <h3>Team Links</h3></div>
            <div class="nav">
              <ul class="nav nav-pills nav-stacked navbar-left">
                <li class="active">
                  <a href="team.php">Roster</a>
                </li>
                <li>
                  <a href="scores.php">Scores &amp; Schedule</a>
                </li><li>
                  <a href="#">Depth Chart</a>
                </li>
                <li>
                  <a href="#">Playbooks</a>
                </li>
                <li>
                  <a href="#">Stats</a>
                </li>
                <li>
                  <a href="#">Injuries</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-md-8">
          <div class="main">
			<div class="team-header">
				<div class="container">
					<div class="row">
						<div class="col-md-3">
								<h3>New York Giants</h3>
								<img src="nfl-logos/19.png">
						</div>
						<div class="col-md-3 col-md-offset-1">
							<div class="middle-col">
								<p>Owned by <a href="profile.php">Roosevelt</a> since: 6/20/14</p>
								<p>Championships: 1</p>
							</div>
						</div>
					</div>
				</div>
			</div>
            <h3>Team Details</h3>
			<p>Total Salary: $125,345,345</p>
			<p>Player count: 1/46 Active, 0 Inactive, 1/53 Total</p>
			 <div class="container">
			 <div class="row">
			 <div class="col-md-9">
			 <div class="panel panel-primary">
            <!-- Default panel contents -->
            <div class="panel-heading">Roster</div>
            <!-- Table -->
			<div class="table-responsive">
            <table class="table">
                <thead>
                  <tr>
                    <th width="10%">Pos</th>
                    <th width="20%">Name</th>
					<th width="20%">Status</th>
                    <th width="15%">Exp</th>
                    <th width="20%">Salary</th>
					<th width="15%"></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>QB</td>
                    <td><a href="player.php">Bob Jones</a></td>
					<td class="healthy">Healthy</td>
                    <td>1</td>
                    <td>$125,345,345</td>
					<td><button type="button" class="btn btn-default">Extend/Restructure Contract</button></td>
                  </tr><tr class="injured">
                    <td>QB</td>
                    <td><a href="player.php">Bob Jones</a></td>
					<td class="healthy">Torn ACL</td>
                    <td>1</td>
                    <td>$125,345,345</td>
					<td><button type="button" class="btn btn-default">Extend/Restructure Contract</button></td>
                  </tr><tr>
                    <td>QB</td>
                    <td><a href="player.php">Bob Jones</a></td>
					<td class="healthy">Healthy</td>
                    <td>1</td>
                    <td>$125,345,345</td>
					<td><button type="button" class="btn btn-default">Extend/Restructure Contract</button></td>
                  </tr><tr>
                    <td>QB</td>
                    <td><a href="player.php">Bob Jones</a></td>
					<td class="healthy">Healthy</td>
                    <td>1</td>
                    <td>$125,345,345</td>
					<td><button type="button" class="btn btn-default">Extend/Restructure Contract</button></td>
                  </tr>
                  
                </tbody>
              </table></div></div></div></div></div>
			  
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
