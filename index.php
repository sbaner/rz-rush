<?php
session_start();
if(isset($_SESSION['userID'])) {
	header('Location: profile.php');
} 	
?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="generator"
    content="HTML Tidy for HTML5 (experimental) for Windows https://github.com/w3c/tidy-html5/tree/c63cc39" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <link href="css/bootstrap.css" rel="stylesheet" />
    <link href="css/main.css" rel="stylesheet" />
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.js"></script>
    <title>RedZone Rush - Home</title>
	
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
              <li class="active">
                <a href="#">Home</a>
              </li>
              <li>
                <a href="register.php">Sign Up</a>
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
          <div class="login-bar">
            <form class="navbar-form navbar-left" id="login-form" name="login-form" method="post" action="login.php" role="login">
              <div class="form-group">
                <input type="text" class="form-control" name="username" placeholder="Username" />
                <br />
                <input type="password" class="form-control" name="password" placeholder="Password" />
                <br />
                <button type="submit" id="login-button" class="btn btn-primary">Login</button>
              </div>
            </form> 
			<?php
			$result = $_GET["result"];
			if ($result=="fail") {
				echo "Wrong username or password. Try again or <a href=\"login/register.php\">sign up</a>.";
			} 
			?>
          </div> 
        </div>
        <div class="col-md-10">
          
			  <div id="carousel" class="carousel slide" data-ride="carousel">
			  <!-- Indicators -->
			  <ol class="carousel-indicators">
				<li data-target="#carousel" data-slide-to="0" class="active"></li>
				<li data-target="#carousel" data-slide-to="1"></li>
				<li data-target="#carousel" data-slide-to="2"></li>
			  </ol>

			  <!-- Wrapper for slides -->
			  <div class="carousel-inner">
				<div class="item active">
					<div class="jumbotron">
						<div class="container">
							<h1>Be the GM</h1>
							<p>Start your professional football franchise</p>
						</div>
					</div>
				</div>
				<div class="item">
				  <div class="jumbotron">
						<div class="container">
						</div>
					</div>
				  <div class="carousel-caption">
					<h3>Tactics and Strategy</h3>
					<p>Nunc luctus dignissim interdum. Quisque mattis congue
                metus eu volutpat.</pa>
				  </div>
				</div>
				<div class="item">
				  <div class="jumbotron">
						<div class="container">
						</div>
					</div>
				  <div class="carousel-caption">
					<h3>Build a Dynasty</h3>
					<p>Quisque mattis congue metus eu volutpat. Sed sed arcu orci. Vivamus non turpis metus.</p>
				  </div>
				</div>
				
			  </div>

			  <!-- Controls -->
			  <a class="left carousel-control" href="#carousel" role="button" data-slide="prev">
				<span class="glyphicon glyphicon-chevron-left"></span>
			  </a>
			  <a class="right carousel-control" href="#carousel" role="button" data-slide="next">
				<span class="glyphicon glyphicon-chevron-right"></span>
			  </a>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-2 col-md-offset-10">
          <div class="credit">
            <p>Image courtesy 
            <a href="http://commons.wikimedia.org/wiki/User:Bgag">Bernard Gagnon</a>, modified in accordance with <a href="http://creativecommons.org/licenses/by-sa/3.0/legalcode">CC BY-SA 3.0</a></p>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
