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
    <title>RedZone Rush - Sign Up</title>
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
              <li>
                <a href="../index.php">Home</a>
              </li>
              <li class="active">
                <a href="#">Sign Up</a>
              </li>
              <li>
                <a href="#">Help</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="row" id="content">
        
        <div class="col-md-offset-3 col-md-6">
          <div class="main">
		  <?php
		  $result = $_GET["result"];
		  
		  if ($result=="sameuser") {
			echo "<p>A user with that name already exists. Please <a href=\"register.php\">try again</a>.</p>";
		  } else if ($result=="sameemail") {
			echo "<p>An account with that email already exists. <a href=\"#\">Forgot your password?</a></p>";
		  } else if ($result=="success") {
			echo "<h3>Success!</h3> <p>Your account has been created. Please check your email for a confirmation email.";
		  } else if ($result=="sameip") {
			echo "<p>An account has already been registered from this computer. Please do not create multiple accounts. If you think this is a mistake, <a href=\"#\">contact us</a>.</p>
				<p>If you've forgotten your username or password, <a href=\"#\">click here</a>.</p>";
		  } else {
			echo "Something went wrong.";
		  }
		  ?>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
