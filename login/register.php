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
	<script>
		function checkForm() {
			var email = $("#email").val();
			var username = $("#username").val();
			var password = $("#password1").val();
			var confirmPassword = $("#password2").val();
			var checkbox = document.getElementById('termcheck');
			var button = document.getElementById('signup-button');

			if (email!='' && username!='' && password!='') {
				if (email.indexOf("@") > 0) {
					if (password == confirmPassword) {
						if (checkbox.checked) {
						$("#divCheckPasswordMatch").html("");
							button.disabled = false;
						} else {
							button.disabled = true;
						}
					} else {
						$("#divCheckPasswordMatch").html("Passwords do not match.");
						button.disabled = true;
					}
				} else {
					$("#divCheckPasswordMatch").html("Invalid email.");
					button.disabled = true;
				}
			} else {
				$("#divCheckPasswordMatch").html("Some fields are empty.");
				button.disabled = true;
			}
		}

		

	</script>
    <title>RedZone Rush - Sign Up</title>
  </head>
  <body>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-2">
          <a href="../index.html">
            <img class="logo" src="../logo-small.png" />
          </a>
        </div>
        <div class="col-md-10">
          <div class="nav">
            <ul class="nav nav-pills navbar-left">
              <li>
                <a href="../index.html">Home</a>
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
      <div class="row">
        
        <div class="col-md-offset-3 col-md-6">
          <div class="main">
		  <h3>Sign Up</h3>
            <form class="form-horizontal" method="POST" id="signup" action="registration.php" role="form">
              <div class="form-group">
                <label for="email" class="col-sm-2 control-label">Email</label>
                <div class="col-sm-10">
                  <input type="email" class="form-control" id="email" name="email" placeholder="Email" onChange="checkForm();"/>
                </div>
              </div>
			  <div class="form-group">
                <label for="username" class="col-sm-2 control-label">Username</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="username" name="username" placeholder="Username" onChange="checkForm();">
                </div>
              </div>
              <div class="form-group">
                <label for="password1" class="col-sm-2 control-label">Password</label>
                <div class="col-sm-10">
                  <input type="password" class="form-control" id="password1" name="password1" placeholder="Password" onChange="checkForm();"/>
                </div>
              </div>
			  <div class="form-group">
                <label for="password2" class="col-sm-2 control-label">Confirm Password</label>
                <div class="col-sm-10">
                  <input type="password" class="form-control" id="password2" name="password2" placeholder="Password" onChange="checkForm();"/>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <div class="checkbox">
                    <label>
                    <input type="checkbox" id="termcheck" onChange="checkForm();"/> I have read and agree to the <a href="#">Terms and Conditions</a></label>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" class="btn btn-default" id="signup-button" disabled>Sign Up</button>
                </div>
              </div><p>Please note: creating multiple accounts is prohibited, and cheaters will be banned.</p>
			  <div id="divCheckPasswordMatch"></div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
