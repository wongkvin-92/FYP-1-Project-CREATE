<?php
	//error_reporting(0);
	session_start();
	include("dbconfig.php");
	$email = $_POST['email'];
	$password = $_POST['password'];

	//to validate if remember me feature is checked and submitted
	$rememberMe = false;
	if(isset($_POST['remember']))
  	$rememberMe = true;

	//submit the login form to check validation and login as a member or trainer
	//based on username and password
	if (isset($_POST['submit'])) {
		//echo "testing 2";
		if (!empty($email)&&!empty($password)) {

			$result = $conn->query("SELECT * FROM admin WHERE adminEmail='$email' and adminPass='$password'");


			 while($rs = $result->fetch_array())
			{
				$_SESSION['admin_ID'] = $rs["adminID"];
				$_SESSION['admin_Name'] = $rs["adminName"];
			  $_SESSION['admin_Email'] = $rs['adminEmail'];
			  $_SESSION['admin_Password'] = $rs['adminPass'];

						// to set cookie for the username field only
						if($rememberMe){
							setcookie ("member_cred", $rs["admin_Email"], time()+ (10 * 365 * 24 * 60 * 60));
						}
						else{
							setcookie("member_cred", "");
						}
			}


			 if (isset($_SESSION['admin_Email']) && $_SESSION['admin_Email'] == $email && $_SESSION['admin_Password'] == $password) {

					echo sprintf('<script type="text/javascript"> alert("Welcome at HELPiCT CREATE: %s");</script>', $_SESSION['admin_Name']);
			 	 	echo  "<script> window.location.assign('homepage.html'); </script>";

			 }else{
			 	    echo '<script language = "javascript">';
            echo 'alert("Username and Password are not found.")';
            echo '</script>';
					  echo  "<script> window.location.assign('index.php'); </script>";
			 }

		}else{
			   echo '<script language = "javascript">';
         echo 'alert("Please fill all the field.")';
         echo '</script>';
         echo  "<script> window.location.assign('index.php'); </script>";
		}
	}



	$conn->close();

?>
