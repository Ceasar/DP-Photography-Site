<?php
include_once("../databaseTools.php");
include_once("../tools.php");
/** 
 * dailypennsylvanian.com GA Story List Package
 *
 * claims.php: Lets a reporter claim an outstanging story.
 * @version  1.0 (1/05)
 * @author   Matthew Jones (mrjones@seas.upenn.edu)
 * 
 */
 ?>

<LINK REL="stylesheet" TYPE="text/css" HREF="../styles.css">
<link rel="shortcut icon" href="../assets/favicon.ico" type="image/x-icon" /> 

<body>
	<a href="http://thedp.com"><img id="flag" src="../assets/flag.png" alt="The Daily Pennsylvanian" /></a> 
	<?php 

	if (isset($_POST['email'])) {
		$query = "SELECT COUNT(*) FROM user WHERE email = '".stripslashes($_POST['email'])."'";
		$result = run_sql($query);
		$count = mysql_result($result, 0);
		if ($count == 0){
			$salt_length = 2;	
	
			//Generate a random salt
			$jumble = md5(time() . getmypid());
			$salt = substr($jumble,0,$salt_length);
			
			$passhash = crypt($_POST['password'], $salt);
			//$query = "INSERT INTO user (id, username, password, name, email, phone, year, permissions) VALUES("
			$query = "INSERT INTO user (passhash, salt, name, email, phone, grad_date, permissions) VALUES('"
				//.addslashes($_POST['username'])."', '"
				.addslashes($passhash)."', '"
				.addslashes($salt)."', '"
				.addslashes($_POST['name'])."', '"
				.addslashes($_POST['email'])."', '"
				.addslashes($_POST['phone'])."', '"
				.addslashes($_POST['year'])."', 'user')";
			$result = run_sql($query);
			?>
			<div align=center>
				<p><?php echo "Your account has been created.";?></p>
			</div>
			<?php
		}
		else{
			?>
			<div align=center>
				<p><?php echo "That username has already been taken.";?></p>
			</div>
			<?php
		}
	}
	else{
		?>
		<br>
		<div class="apps_message" style="text-align: center;"><?php echo "Create your accout."; ?></a></div><p>
			<form name="create" method="post" action="create.php">
			<table class="apps_table" align=center>
				<!--<tr>
					<td>
						Username:
					</td>
					<td>
						<input type="text" name="username">
					</td>
				</tr>-->
				<tr>
					<td>
						Email:
					</td>
					<td>
						<input type="text" name="email">
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
					<td>
						Name:
					</td>
					<td>
						<input type="text" name="name">
					</td>
				</tr>
				<tr>
					<td>
						Phone:
					</td>
					<td>
						<input type="text" name="phone">
					</td>
				</tr>
				<tr>
					<td>
						Graduation Year:
					</td>
					<td>
						<input type="text" name="year">
					</td>
				</tr>
				<tr>
					<td colspan=2 align="center">
						<input type=submit value="Confirm">
					</td>
				</tr>
			</table>
		</form>
	<?php
	}
	?>
<p><a href="../index.php">Return to story list.</a>
</body>