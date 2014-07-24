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
    <link href="css/test.css" rel="stylesheet" />
	<link rel="shortcut icon" href="favicon.ico" />
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.js"></script>
    <title>RedZone Rush</title>
	
  </head>
  <body>
    <div class="container-fluid">
      <div class="row" id="top">
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
      <div class="row" id="content">
	  <div class="col-md-2 col-md-offset-1">
          <div class="login-bar">
		  <h1>Log in</h1>
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
			if(isset($_GET["result"])) {
				$result = $_GET["result"];
				if ($result=="fail") {
					echo "Wrong username or password. Try again or <a href=\"login/register.php\">sign up</a>.";
				} 
			}
			?>
          </div> 
        </div>
		<div class="col-md-7">
		<div id="carousel" class="carousel slide" data-ride="carousel">
		  <!-- Indicators -->
		  <ol class="carousel-indicators">
			<li data-target="#carousel" data-slide-to="0" class="active"></li>
			<li data-target="#carousel" data-slide-to="1"></li>
		  </ol>

		  <!-- Wrapper for slides -->
		  <div class="carousel-inner">
			<div class="item active">
			  <img src="images/carousel2.jpg" alt="">
			  <div class="carousel-caption">
				<h3>Build a Dynasty</h3>
				<p>Draft rookies, sign free agents, make playbooks, and lead your team to the championship!</p>
			  </div>
			</div>
			<div class="item">
			  <img src="images/carousel1.jpg" alt="">
			  <div class="carousel-caption">
				<h3>Football in Every Size</h3>
				<p>Sign free agents, draft rookies, and lead your team to the championship!</p>
			  </div>
			</div>
		  </div>
		</div>
      </div>
    </div>
  </body>
</html>
