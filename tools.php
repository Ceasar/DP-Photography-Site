<?php 
/** 
 * dailypennsylvanian.com GA Story List Package
 *
 * tools.php: Toolkit for GA Stories.
 * @version  1.0 (1/05)
 * @author   Matthew Jones (mrjones@seas.upenn.edu)
 * 
 */
function udate($format, $utimestamp = null)
{
    if (is_null($utimestamp))
        $utimestamp = microtime(true);

    $timestamp = floor($utimestamp);
    $milliseconds = round(($utimestamp - $timestamp) * 1000000);

    return date(preg_replace('`(?<!\\\\)u`', $milliseconds, $format), $timestamp);
}

function display_unclaimed_assignments() {
	$today = udate("Y")."-".date("m")."-".(date("d")-1);
	$query = "SELECT * FROM story WHERE date > '".$today."'".
			"AND storyid NOT IN(
				SELECT storyid
				FROM claims
				WHERE approved)
			ORDER BY date ASC, time ASC";
	//echo udate('h i s u')." ";
	$result = run_sql($query);
	//echo udate('h i s u')." ";
	?>
	<div class=m>Unclaimed GA Assignments</div>
	<tr><td colspan="2"><img src="assets/red.gif" width="100%" height="2" vspace="3"></td></tr>
	<br>
	<div class="indent">
		<table border=1><?php 
	while ($row = mysql_fetch_array($result)){ ?>
		
			<tr>
				<td class=\"ga_event_cell\" valign=\"top\">
					<table border=1>
						<tr><td width=75%><b>Event: </b><?php echo stripslashes($row['title']); ?></td></tr>
						<tr><td width=75%><b>Time: </b><?php echo date("F j, Y", strtotime($row['date']))." ".date("g:ia", strtotime($row['time'])); ?></td></tr>
						<tr><td width=75%><b>Location: </b><?php echo stripslashes($row['location']); ?></td></tr>
						<tr><td width=75%><?php echo stripslashes($row['description']); ?></td></tr>
					</table>
				</td>
				<td class=\"ga_event_cell\" valign=\"top\" width=25%>
					<table border=1><?php
				if (isset($_SESSION['userid'])) {
					$query = "SELECT * FROM claims WHERE userid = '".$_SESSION['userid']."' AND storyid = '".$row['storyid']."'";
					$result2 = run_sql($query);
					if (mysql_fetch_array($result2)){ ?>
						<tr><td>Pending approval</td></tr><?php
					}
					else{ ?>
						<form name="claim" method="post" action="claim.php?storyid=<?php echo $row['storyid']; ?>">
							<tr><td><input type=submit name="claim" value="Claim"></td></tr>
						</form><?php
					}
					if ($_SESSION['permissions'] == "admin"){ ?>
						<form name="admin" method="post" action="admin/edit.php?storyid=<?php echo $row['storyid']; ?>">
							<tr><td><input type=submit name="edit" value="Edit"></td></tr>
							<tr><td><input type=submit name="delete" value="Delete"></td></tr>
						</form>
						<?php
					}
				}?>
					</table>
				</td>
			</tr>
		<?php
		} ?>
		</table>
	</div><?php
}

//To be called before display_pending_assignment. Updates database with approved stories.
function approve(){
	$today = date("Y")."-".date("m")."-".(date("d")-1);
	$query = "SELECT DISTINCT storyid FROM claims WHERE approved = 0";
	$result = run_sql($query);
	while ($row = mysql_fetch_array($result)){
		if (isset($_POST[$row['storyid']])){
			$storyid = $row['storyid'];
			$userid = $_POST[$row['storyid']];
			$query = "UPDATE claims SET approved = 1 WHERE storyid=".$storyid." AND userid=".$userid;
			run_sql($query);
		}
	}
}

 //Prints the pending assignments.
function display_pending_assignments() {
	$today = date("Y")."-".date("m")."-".(date("d")-1);
	$query = "SELECT DISTINCT storyid FROM claims WHERE NOT EXISTS
		(SELECT * FROM claims AS claims2 WHERE claims.storyid = claims2.storyid
		AND claims2.approved)";
	// $query = "SELECT * FROM (SELECT userid, storyid FROM claims WHERE approved = 0) AS unapproved_claim,
			// (SELECT storyid, title, date, time FROM story WHERE date > '".$today."') AS current,
			// (SELECT userid, name, email, phone FROM user) AS person
			// WHERE unapproved_claim.userid = person.userid AND unapproved_claim.storyid = current.storyid";
	//echo udate('h i s u')." ";
	$result = run_sql($query);?>
	<div class=m>Pending GA Assignments</div>
	<tr><td colspan="2"><img src="assets/blue.gif" width="100%" height="2" vspace="3"></td></tr><?php
	//echo udate('h i s u')." ";;
	if (mysql_num_rows($result) > 0){
		?>
		<div class="indent">
			<form name="approve" method="post" action="index.php">
			<table border=1><?php
		while ($row = mysql_fetch_array($result)){
				$query = "SELECT title, date FROM story WHERE storyid='".$row['storyid']."'";
				$story = run_sql($query);
				$title = mysql_fetch_array($story);?>
				<tr>
					<td>
						<table border=1>
							<tr>
								<td><b>Event: </b><?php echo stripslashes($title['title']); ?></td>
								<td><b>Date: </b><?php echo date('F j, Y',strtotime($title['date'])); ?></td>
							</tr><?php
				$query = "SELECT name, email, phone, claims.userid
					FROM claims INNER JOIN user ON claims.userid = user.userid
					WHERE storyid='".$row['storyid']."'";
				$claimers = run_sql($query);
				while ($row2 = mysql_fetch_array($claimers)){?>
							<tr>
								<td><b>Claimed By: </b><?php echo stripslashes($row2['name']); ?></td>
								<td><b>Email: </b><?php echo stripslashes($row2['email']); ?></td>
								<td><b>Cell Phone: </b><?php echo stripslashes($row2['phone']); ?></td>
								<td><input type=radio name="<?php echo $row['storyid']; ?>" value="<?php echo $row2['userid']; ?>"></td>
							</tr><?php
					}?>
						</table>
					</td>
				</tr><?php
			}?>
				<tr>
					<td><input type=submit value="Approve"></td>
				</tr>
			</table>
			</form>
		</div><?php
	}
}

function display_claimed_assignments() {
	$today = date("Y")."-".date("m")."-".(date("d")-1);
	$query = "SELECT * FROM (SELECT * FROM story WHERE date > '".$today."') AS current
			INNER JOIN (SELECT userid, storyid FROM claims WHERE approved) AS approved_claim ON current.storyid = approved_claim.storyid
			INNER JOIN (SELECT userid, name, email, phone FROM user) AS person ON approved_claim.userid = person.userid
			ORDER BY date ASC, time ASC";
	//echo udate('h i s u')." ";
	$result = run_sql($query);
	//echo udate('h i s u')." ";
	?>
	<div class=m>Claimed GA Assignments</div>
	<tr><td colspan="2"><img src="assets/blue.gif" width="100%" height="2" vspace="3"></td></tr>
	<br>
	<div class="indent"><?php
	while ($row = mysql_fetch_array($result)){?>
			<table border=1>
				<td class=\"ga_event_cell\" valign=\"top\">
					<table border=1>
						<tr><td width=75%><b>Event: </b><?php echo stripslashes($row['title']); ?></td></tr>
						<tr><td width=75%><b>Time: </b><?php echo date("F j, Y", strtotime($row['date']))." ".date("g:ia", strtotime($row['time'])); ?></td></tr>
						<tr><td width=75%><b>Location: </b><?php echo stripslashes($row['location']); ?></td></tr>
						<tr><td width=75%><?php echo stripslashes($row['description']); ?></td></tr>
					</table>
				</td>
				<td class=\"ga_event_cell\" valign=\"top\" width=25%>
					<table border=1>
						<tr><td><b>Claimed By: </b><?php echo $row['name']; ?></td></tr>
						<tr><td><b>Email: </b><?php echo $row['email']; ?></td></tr>
						<tr><td><b>Cell Phone: </b><?php echo $row['phone']; ?></td></tr><?php
					if (isset($_SESSION['permissions']) && $_SESSION['permissions'] == "admin"){ ?>
						<form name="admin" method="post" action="admin/edit.php?storyid=<?php echo $row['storyid']; ?>">
							<tr><td><input type=submit name="edit" value="Edit"></td></tr>
							<tr><td><input type=submit name="delete" value="Delete"></td></tr>
						</form><?php
					}?>
					</table>
				</td>
			</table><?php
	}?>
	</div><?php
	//echo udate('h i s u')." ";
}
?>