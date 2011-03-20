<?php
if(!isset($_SESSION)) {session_start();}
/** 
 * dailypennsylvanian.com GA Story List Package
 *
 * logout.php: Logs a user out.
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
	<div align=center>
		<p>
		<?php
		if (isset($_SESSION['userid'])) {
			session_destroy();
			echo "You have sucessfully logged out.";
		}
		else {
			echo "How did you get here?";
		}
		?>
		</p>
		<br>
		<p><a href="../index.php">Return to index.</a></p>
	</div>
</body>