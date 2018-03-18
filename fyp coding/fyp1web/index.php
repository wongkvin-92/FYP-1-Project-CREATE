<?php
  session_start();

?>
<!DOCTYPE html>
<html lang="en" >

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Responsive Flexbox Login Modal</title>

      <link href="css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="css/style.css">
</head>

<body>

  <div class="container"  id="login-div">
	<div class="left"></div>
	<div class="right">
		<div class="formbox">
			<div class="borderForm">
				 <p>Email</p>
				 <input type="email" name="email" placeholder="Email" id="email" required/>
				 <p>Password</p>
				 <input type="password" name="password" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;" id="pass" required/>
				 <button type="submit" name="submit" class="btnSubmit" id="btnLogin">Login</button>
         <div class="squaredTwo">
      <input type="checkbox" name="remember" value="None" id="squaredTwo" name="check" />
      <label for="squaredTwo"></label> <span class="checkboxLabel">Remember Me</span>
    </div><br/>
         <!-- <a href="#">Forgot Password？</a> -->
			</div>
		</div>


	</div>
</div>
<!--
<div id="user-div">
  <h1>Hi Welcome,</h1>
  <p id="user-para">You're logged in!</p>
    <button onclick="logout()" class="btnSubmit"> logout </button>
</div>
-->
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="js/jquery.cookie.js"></script>
    <!--<script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>-->

  <script src="js/bootstrap.min.js"></script>
  <script type="text/javascript" src="index.js"></script>

</body>

</html>
