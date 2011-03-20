<?php
require_once("/../databaseTools.php");
session_start(); 

/*
 * User authentication toolkit by Matt Jones (mrjones@seas.upenn.edu) 
 * Version 1.0 - December, 2004
 * 
 * This is pretty standard PHP session authentication.  It's secure enough
 *      to keep out snoopers, but will probably fall to a determined hacker
 *      If apps.dailypennsylvanian.com ever goes big time, we'll need to at
 *      least bullet-proof this code (buffer overflows and the like), but 
 *      preferably switch to HTTP, or best get Digital Partners to support
 *      some sort of certificate authentication (SSL/TLS).
 */


/* 
 * auth_create_user()
 * add a user to the database.  by default they have no access rights
 */
function auth_create_user($username, $password, $email, $phone, $name, $grad_date, $ip) {
	
	//server seems to be using standard DES hashing
	//TODO: Can we get something stronger?
	$salt_length = 2;	
	
	//Generate a random salt
	$jumble = md5(time() . getmypid());
	$salt = substr($jumble,0,$salt_length);
	
	$passhash = crypt($password, $salt);
	
	//Generate query and insert into database
	$query = "INSERT INTO dp_users ( username, passhash, salt, active, email, phone, name, grad_date, privileges, ipaddress, created)
		VALUES ('".addslashes($username)."', '".addslashes($passhash)."', '".$salt."', '1', '".addslashes($email)."', '".addslashes($phone)."', '".addslashes($name)."', '".$grad_date."', 0, '".$ip."', NOW())";	
	run_sql($query);
	mail("ceasarb@seas.upenn.edu","[DP_APPS] Account Request",$username ."(" . $email .") has requested an account.\n\nhttp://apps.dailypennsylvanian.com/users/pending.php");
}

/* 
 * auth_check_login()
 * checks if (username, password) is in the database, returns true or false
 * if the user is authenticated, a session is started for them
 */
function auth_check_login($email, $password) {
	$query = "SELECT * FROM user WHERE email = '".addslashes($email)."'";
	$result = run_sql($query);
	$db_field = mysql_fetch_assoc($result);
	
	if (mysql_num_rows($result)) {
		$salt = mysql_result($result, 0, "salt");
		$passhash = mysql_result($result, 0, "passhash");
		$userid = mysql_result($result, 0, "userid");
		if (crypt($password, $salt) == $passhash) {
			$permissions = mysql_result($result, 0, "permissions");
			auth_start_session($userid, $email, $permissions);
			return true;
		}
	}
	return false;
}


/* 
 * auth_start_session()
 * starts a session for $username
 */

function auth_start_session($userid, $email, $permissions) {
	$_SESSION['userid'] = $userid;
	$_SESSION['email'] = $email;
	$_SESSION['permissions'] = $permissions;
	$_SESSION['active'] = true;
}

/* 
 * auth_logoff()
 * logoff/destroy session
 */
function auth_logoff() {
	if ($_SESSION['active']) {
		session_destroy();
	}

	include_once("../includes/noauth_header.php");
	
	?>
	<div class="apps_message" style="text-align: center">You have been logged off</div>
	<?php
}

/* 
 * auth_login_page()
 * display a login form
 */
function auth_login_page($message) {
	?>
 		<div class="apps_message" style="text-align: center;"><?php echo $message; ?></a></div><p>
		<?php if(isset($_GET['refpage'])) { ?>		
			<form name="dpappslogin" method="post" action="index.php?refpage=<?php echo $_GET['refpage']; ?>">
		<?php } else { ?>
			<form name="dpappslogin" method="post" action="index.php">
		<?php } ?>
			<table class="apps_table" align=center>
				<tr>
					<td>
						Username:
					</td>
					<td>
						<input type="text" name="username">
					</td>
				</tr>
				<tr>
					<td>
						Password:
					</td>
					<td>
						<input type="password" name="password">
					</td>
				</tr>
				
				<tr>
					<td colspan=2 align="center">
						<input type=submit value="Login">
					</td>
				</tr>
			</table>
		</form>
	<?php
}

function auth_redirect($url) {
?>
	<SCRIPT LANGUAGE="JavaScript">
		<!--
		window.location="<?php echo $url;?>";
		// -->
	</script>
<?php
}
?>