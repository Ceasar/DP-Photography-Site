<?php
ini_set("SMTP","mail.MyWebSite.com");
ini_set("sendmail_from","webmaster@MyWebSite.com");
include_once("databaseTools.php");
include_once("tools.php");
if(!isset($_SESSION)) {session_start();}
/** 
 * dailypennsylvanian.com GA Story List Package
 *
 * claims.php: Confirms that a user has claimed a story.
 * @version  2.0 (2/26/2011)
 * @author   Ceasar Bautista (ceasarb@seas.upenn.edu)
 * 
 */
 ?>

<LINK REL="stylesheet" TYPE="text/css" HREF="styles.css">
<link rel="shortcut icon" href="assets/favicon.ico" type="image/x-icon" /> 

<body>
	<a href="http://thedp.com"><img id="flag" src="assets/flag.png" alt="The Daily Pennsylvanian" /></a> 
	<div class=\"apps_pageheader\">Claim Assignment</div>
	<tr> 
		<td colspan="2"><img src="assets/red.gif" width="100%" height="2" vspace="3"></td>
	</tr>
	<?php
	if (isset($_GET['storyid']) && isset($_SESSION['userid'])) {
		$query = "SELECT * FROM story WHERE storyid = '".$_GET['storyid']."'
			AND NOT EXISTS (SELECT * FROM claims
			WHERE storyid = '".$_GET['storyid']."' AND claims.userid = '".$_SESSION['userid']."')";
		$result = run_sql($query);
		if(mysql_fetch_array($result)){
			echo "Thanks for claiming the story. Your request is pending approval.";
			$query = "INSERT INTO claims (userid, approved, storyid)
				VALUES(".$_SESSION['userid'].", false, ".$_GET['storyid'].")";
			run_sql($query);
			
			$query = "SELECT * FROM user WHERE userid = '".$_SESSION['userid']."'";
			$userdata = run_sql($query);
			$to = mysql_result($userdata, 0, "email");
			$subject = "[DP_GA] Confirmation for story: " . mysql_result($result, 0, "title");
			$body = "This email is to confirm that ".mysql_result($userdata, 0, "name").
					" has claimed the story titled ".mysql_result($result, 0, "title").
					" on ".mysql_result($result,0,"date") . " at " . 
					mysql_result($result, 0, "time")." at " . 
					mysql_result($result, 0, "location") . "\n\nPlease do not need to reply to this email.";
			//$from = "mrjones@seas.upenn.edu";
		
			//mail($to, $subject, $body); //"From: $from\r\n" . "Reply-To: $from\r\n" . "X-Mailer: PHP/" . phpversion());
			//mail("ceasarb@seas.upenn.edu", $subject, $body);
		}
		else {
			echo "This story has already been claimed.";
		}
	}
	?>
<p><a href="index.php">Return to story list.</a>
</body>