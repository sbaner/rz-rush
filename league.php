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
    <link href="css/league.css" rel="stylesheet" />
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.js"></script>
    <title>RedZone Rush - League</title>
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
              <li class="active dropdown">
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
			
            <h3>League Links</h3></div>
            <div class="nav">
              <ul class="nav nav-pills nav-stacked navbar-left">
                <li class="active">
                  <a href="league.php">Standings</a>
                </li>
                <li>
                  <a href="scores.php">Scores &amp; Schedule</a>
                </li>
                <li>
                  <a href="#">Free Agents</a>
                </li>
                <li>
                  <a href="leaguealmanac.php">Almanac</a>
                </li><li>
                  <a href="#">Message Board</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-md-8">
          <div class="main">
            <h3>League</h3>
            <p>2014 Standings, regular season (Note: All  NFL names and logos are for placeholder purposes only.)</p>
            <div class="panel panel-secondary">
              <!-- Default panel contents -->
              <div class="panel-heading">AFC East</div>
              <!-- Table --><div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th width="5%"></th>
                    <th width="15%"></th>
                    <th width="8%">W</th>
                    <th width="8%">L</th>
                    <th width="8%">T</th>
                    <th width="8%">PCT</th>
                    <th width="8%">GB</th>
                    <th width="8%" class="nomobile">PF</th>
                    <th width="8%" class="nomobile">PA</th>
                    <th width="8%" class="nomobile">CONF</th>
                    <th width="8%" class="nomobile">DIV</th>
                    <th width="8%" class="nomobile">STRK</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <a href="team.php"><img src="nfl-logos/17.png" height="40px" /></a>
                    </td>
                    <td><a href="team.php">New England
                    <br />Patriots</a></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">-</td>
                  </tr>
                  <tr>
                    <td>
                      <a href="team.php"><img src="nfl-logos/20.png" height="40px" /></a>
                    </td>
                    <td><a href="team.php">New York
                    <br />Jets</a></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">-</td>
                  </tr>
                  <tr>
                    <td>
                      <a href="team.php"><img src="nfl-logos/15.png" height="40px" /></a>
                    </td>
                    <td><a href="team.php">Miami
                    <br />Dolphins</a></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">-</td>
                  </tr>
                  <tr>
                    <td>
                      <a href="team.php"><img src="nfl-logos/2.png" height="40px" /></a>
                    </td>
                    <td><a href="team.php">Buffalo
                    <br />Bills</a></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">-</td>
                  </tr>
                  <tr>
                    <td></td>
                  </tr>
                </tbody>
              </table></div>
            </div>
			<div class="panel panel-secondary">
              <!-- Default panel contents -->
              <div class="panel-heading">AFC North</div>
              <!-- Table --><div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th width="5%"></th>
                    <th width="15%"></th>
                    <th width="8%">W</th>
                    <th width="8%">L</th>
                    <th width="8%">T</th>
                    <th width="8%">PCT</th>
                    <th width="8%">GB</th>
                    <th width="8%" class="nomobile">PF</th>
                    <th width="8%" class="nomobile">PA</th>
                    <th width="8%" class="nomobile">CONF</th>
                    <th width="8%" class="nomobile">DIV</th>
                    <th width="8%" class="nomobile">STRK</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <a href="team.php"><img src="nfl-logos/4.png" height="40px" /></a>
                    </td>
                    <td><a href="team.php">Cincinnati
                    <br />Bengals</a></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">-</td>
                  </tr>
                  <tr>
                    <td>
                      <a href="team.php"><img src="nfl-logos/23.png" height="40px" /></a>
                    </td>
                    <td><a href="team.php">Pittsburgh
                    <br />Steelers</a></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">-</td>
                  </tr>
                  <tr>
                    <td>
                      <a href="team.php"><img src="nfl-logos/31.png" height="40px" /></a>
                    </td>
                    <td><a href="team.php">Baltimore
                    <br />Ravens</a></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">-</td>
                  </tr>
                  <tr>
                    <td>
                      <a href="team.php"><img src="nfl-logos/5.png" height="40px" /></a>
                    </td>
                    <td><a href="team.php">Cleveland
                    <br />Browns</a></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">-</td>
                  </tr>
                  <tr>
                    <td></td>
                  </tr>
                </tbody>
              </table></div>
            </div>
			<div class="panel panel-secondary">
              <!-- Default panel contents -->
              <div class="panel-heading">AFC South</div>
              <!-- Table --><div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th width="5%"></th>
                    <th width="15%"></th>
                    <th width="8%">W</th>
                    <th width="8%">L</th>
                    <th width="8%">T</th>
                    <th width="8%">PCT</th>
                    <th width="8%">GB</th>
                    <th width="8%" class="nomobile">PF</th>
                    <th width="8%" class="nomobile">PA</th>
                    <th width="8%" class="nomobile">CONF</th>
                    <th width="8%" class="nomobile">DIV</th>
                    <th width="8%" class="nomobile">STRK</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <a href="team.php"><img src="nfl-logos/11.png" height="40px" /></a>
                    </td>
                    <td><a href="team.php">Indianapolis
                    <br />Colts</a></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">-</td>
                  </tr>
                  <tr>
                    <td>
                      <a href="team.php"><img src="nfl-logos/10.png" height="40px" /></a>
                    </td>
                    <td><a href="team.php">Tennessee
                    <br />Titans</a></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">-</td>
                  </tr>
                  <tr>
                    <td>
                      <a href="team.php"><img src="nfl-logos/30.png" height="40px" /></a>
                    </td>
                    <td><a href="team.php">Jacksonville
                    <br />Jaguars</a></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">-</td>
                  </tr>
                  <tr>
                    <td>
                      <a href="team.php"><img src="nfl-logos/32.png" height="40px" /></a>
                    </td>
                    <td><a href="team.php">Houston
                    <br />Texans</a></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">-</td>
                  </tr>
                  <tr>
                    <td></td>
                  </tr>
                </tbody>
              </table></div>
            </div>
			<div class="panel panel-secondary">
              <!-- Default panel contents -->
              <div class="panel-heading">AFC West</div>
              <!-- Table --><div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th width="5%"></th>
                    <th width="15%"></th>
                    <th width="8%">W</th>
                    <th width="8%">L</th>
                    <th width="8%">T</th>
                    <th width="8%">PCT</th>
                    <th width="8%">GB</th>
                    <th width="8%" class="nomobile">PF</th>
                    <th width="8%" class="nomobile">PA</th>
                    <th width="8%" class="nomobile">CONF</th>
                    <th width="8%" class="nomobile">DIV</th>
                    <th width="8%" class="nomobile">STRK</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <a href="team.php"><img src="nfl-logos/7.png" height="40px" /></a>
                    </td>
                    <td><a href="team.php">Denver
                    <br />Broncos</a></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">-</td>
                  </tr>
                  <tr>
                    <td>
                      <a href="team.php"><img src="nfl-logos/12.png" height="40px" /></a>
                    </td>
                    <td><a href="team.php">Kansas City
                    <br />Chiefs</a></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">-</td>
                  </tr>
                  <tr>
                    <td>
                      <a href="team.php"><img src="nfl-logos/24.png" height="40px" /></a>
                    </td>
                    <td><a href="team.php">San Diego
                    <br />Chargers</a></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">-</td>
                  </tr>
                  <tr>
                    <td>
                      <a href="team.php"><img src="nfl-logos/13.png" height="40px" /></a>
                    </td>
                    <td><a href="team.php">Oakland
                    <br />Raiders</a></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">-</td>
                  </tr>
                  <tr>
                    <td></td>
                  </tr>
                </tbody>
              </table></div>
            </div>
            <div class="panel panel-primary">
            <!-- Default panel contents -->
            <div class="panel-heading">NFC East</div>
            <!-- Table --><div class="table-responsive">
            ...
            <table class="table">
                <thead>
                  <tr>
                    <th width="5%"></th>
                    <th width="15%"></th>
                    <th width="8%">W</th>
                    <th width="8%">L</th>
                    <th width="8%">T</th>
                    <th width="8%">PCT</th>
                    <th width="8%">GB</th>
                    <th width="8%" class="nomobile">PF</th>
                    <th width="8%" class="nomobile">PA</th>
                    <th width="8%" class="nomobile">CONF</th>
                    <th width="8%" class="nomobile">DIV</th>
                    <th width="8%" class="nomobile">STRK</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <a href="team.php"><img src="nfl-logos/21.png" height="40px" /></a>
                    </td>
                    <td><a href="team.php">Philadelphia
                    <br />Eagles</a></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">-</td>
                  </tr>
                  <tr>
                    <td>
                      <a href="team.php"><img src="nfl-logos/6.png" height="40px" /></a>
                    </td>
                    <td><a href="team.php">Dallas
                    <br />Cowboys</a></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">-</td>
                  </tr>
                  <tr class="myteam">
                    <td>
                      <a href="team.php"><img src="nfl-logos/19.png" height="40px" /></a>
                    </td>
                    <td><a href="team.php">New York
                    <br />Giants</a></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">-</td>
                  </tr>
                  <tr>
                    <td>
                      <a href="team.php"><img src="nfl-logos/28.png" height="40px" /></a>
                    </td>
                    <td><a href="team.php">Washington
                    <br />Redskins</a></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">-</td>
                  </tr>
                  <tr>
                    <td></td>
                  </tr>
                </tbody>
              </table></div></div>
			  <div class="panel panel-primary">
            <!-- Default panel contents -->
            <div class="panel-heading">NFC North</div>
            <!-- Table --><div class="table-responsive">
            ...
            <table class="table">
                <thead>
                  <tr>
                    <th width="5%"></th>
                    <th width="15%"></th>
                    <th width="8%">W</th>
                    <th width="8%">L</th>
                    <th width="8%">T</th>
                    <th width="8%">PCT</th>
                    <th width="8%">GB</th>
                    <th width="8%" class="nomobile">PF</th>
                    <th width="8%" class="nomobile">PA</th>
                    <th width="8%" class="nomobile">CONF</th>
                    <th width="8%" class="nomobile">DIV</th>
                    <th width="8%" class="nomobile">STRK</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <a href="team.php"><img src="nfl-logos/9.png" height="40px" /></a>
                    </td>
                    <td><a href="team.php">Green Bay
                    <br />Packers</a></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">-</td>
                  </tr>
                  <tr>
                    <td>
                      <a href="team.php"><img src="nfl-logos/3.png" height="40px" /></a>
                    </td>
                    <td><a href="team.php">Chicago
                    <br />Bears</a></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">-</td>
                  </tr>
                  <tr>
                    <td>
                      <a href="team.php"><img src="nfl-logos/8.png" height="40px" /></a>
                    </td>
                    <td><a href="team.php">Detroit
                    <br />Lions</a></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">-</td>
                  </tr>
                  <tr>
                    <td>
                      <a href="team.php"><img src="nfl-logos/16.png" height="40px" /></a>
                    </td>
                    <td><a href="team.php">Minnesota
                    <br />Vikings</a></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">-</td>
                  </tr>
                  <tr>
                    <td></td>
                  </tr>
                </tbody>
              </table></div></div>
			  <div class="panel panel-primary">
            <!-- Default panel contents -->
            <div class="panel-heading">NFC South</div>
            <!-- Table --><div class="table-responsive">
            ...
            <table class="table">
                <thead>
                  <tr>
                    <th width="5%"></th>
                    <th width="15%"></th>
                    <th width="8%">W</th>
                    <th width="8%">L</th>
                    <th width="8%">T</th>
                    <th width="8%">PCT</th>
                    <th width="8%">GB</th>
                    <th width="8%" class="nomobile">PF</th>
                    <th width="8%" class="nomobile">PA</th>
                    <th width="8%" class="nomobile">CONF</th>
                    <th width="8%" class="nomobile">DIV</th>
                    <th width="8%" class="nomobile">STRK</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <a href="team.php"><img src="nfl-logos/29.png" height="40px" /></a>
                    </td>
                    <td><a href="team.php">Carolina
                    <br />Panthers</a></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">-</td>
                  </tr>
                  <tr>
                    <td>
                      <a href="team.php"><img src="nfl-logos/18.png" height="40px" /></a>
                    </td>
                    <td><a href="team.php">New Orleans
                    <br />Saints</a></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">-</td>
                  </tr>
                  <tr>
                    <td>
                      <a href="team.php"><img src="nfl-logos/27.png" height="40px" /></a>
                    </td>
                    <td><a href="team.php">Tampa Bay
                    <br />Buccaneers</a></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">-</td>
                  </tr>
                  <tr>
                    <td>
                      <a href="team.php"><img src="nfl-logos/1.png" height="40px" /></a>
                    </td>
                    <td><a href="team.php">Atlanta
                    <br />Falcons</a></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">-</td>
                  </tr>
                  <tr>
                    <td></td>
                  </tr>
                </tbody>
              </table></div></div>
			  <div class="panel panel-primary">
            <!-- Default panel contents -->
            <div class="panel-heading">NFC West</div>
            <!-- Table --><div class="table-responsive">
            ...
            <table class="table">
                <thead>
                  <tr>
                    <th width="5%"></th>
                    <th width="15%"></th>
                    <th width="8%">W</th>
                    <th width="8%">L</th>
                    <th width="8%">T</th>
                    <th width="8%">PCT</th>
                    <th width="8%">GB</th>
                    <th width="8%" class="nomobile">PF</th>
                    <th width="8%" class="nomobile">PA</th>
                    <th width="8%" class="nomobile">CONF</th>
                    <th width="8%" class="nomobile">DIV</th>
                    <th width="8%" class="nomobile">STRK</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <a href="team.php"><img src="nfl-logos/26.png" height="40px" /></a>
                    </td>
                    <td><a href="team.php">Seattle
                    <br />Seahawks</a></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">-</td>
                  </tr>
                  <tr>
                    <td>
                      <a href="team.php"><img src="nfl-logos/25.png" height="40px" /></a>
                    </td>
                    <td><a href="team.php">San Francisco
                    <br />49ers</a></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">-</td>
                  </tr>
                  <tr>
                    <td>
                      <a href="team.php"><img src="nfl-logos/22.png" height="40px" /></a>
                    </td>
                    <td><a href="team.php">Arizona
                    <br />Cardinals</a></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">-</td>
                  </tr>
                  <tr>
                    <td>
                      <a href="team.php"><img src="nfl-logos/14.png" height="40px" /></a>
                    </td>
                    <td><a href="team.php">St. Louis
                    <br />Rams</a></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">0-0</td>
                    <td class="nomobile">-</td>
                  </tr>
                  <tr>
                    <td></td>
                  </tr>
                </tbody>
              </table></div></div>
			  <div class="container">
				<div class="row">
					<div class="col-md-3">
						<h4>League Info</h4>
					</div>
				</div>
				<div class="row">
					<div class="col-md-1">
						Frequency: Every day
					</div><div class="col-md-1">
						Salary cap: Standard
					</div><div class="col-md-1">
						Injuries: Standard
					</div><div class="col-md-2">
						Non-CPU players: 1/32
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
