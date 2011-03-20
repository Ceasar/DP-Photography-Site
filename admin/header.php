<?php
include_once("../authentication/authentication_toolkit.php");
if ($_SESSION['active']){
	if ($_SESSION['permissions'] != 'admin') {
			echo "<div class=auth_message style=\"text-align:center\">This area is outside of your current permissions, contact your administrator.</div>";
			exit(0);
		}
}
else{
?>
<LINK REL="stylesheet" TYPE="text/css" HREF="../styles.css">
<p><div class="auth_message" style="text-align: center">You must be logged in to access this page</p>
<a href="../authentication/login.php">Login</a>
<?php
exit(0);
}
?>




