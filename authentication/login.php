<?php
include_once("authentication_toolkit.php");
if(!isset($_SESSION)) {session_start();}
/** 
 * dailypennsylvanian.com GA Story List Package
 *
 * login.php: Allows users to login and request accounts.
 * @version  2.0 (2/26/2011)
 * @author   Ceasar Bautista (ceasarb@seas.upenn.edu)
 * 
 */
?>
<title>lamp.dailypennsylvanian.com</title>
<link rel="stylesheet" type="text/css" href="../styles.css">
<link rel="shortcut icon" href="../assets/favicon.ico" type="image/x-icon" /> 

<body>
	<a href="http://thedp.com"><img id="flag" src="../assets/flag.png" alt="The Daily Pennsylvanian" /></a><br> 
	<p align=center>
	<?php
	if (isset($_SESSION['userid'])) {
		echo "You are already logged in.";
		//auth_redirect("index.php");
	}
	else {
		if (isset($_POST['email'])) {
			if (auth_check_login($_POST['email'], $_POST['password'])) {
				echo "You have sucessfully logged in.";
			}
			else{
				auth_login_page("Login Failed.");
			}
		}
		else {
			auth_login_page("Welcome to lamp.dailypennsylvanian.com.");
		}
	}
	?>
	</p>
	<br>
		<p align="center">
			Member of the staff, but not registered yet?<br>
			<a href="create.php">Request an account</a>.
		</p>
		<p align=center><a href="../index.php">Return to index.</a></p>
</body>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-631054-2";
urchinTracker();
</script>