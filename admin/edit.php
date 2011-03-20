<?php
include_once("header.php");
include_once("time_toolkit.php");
include_once("../databaseTools.php"); 
include_once("../tools.php");
/** 
 * dailypennsylvanian.com GA Story List Package
 *
 * edit.php: Allows admins to add, edit, and delete stories.
 * @version  2.0 (2/26/2011)
 * @author   Ceasar Bautista (mrjones@seas.upenn.edu)
 * 
 */
 ?>
<LINK REL="stylesheet" TYPE="text/css" HREF="../styles.css">
<link rel="shortcut icon" href="../assets/favicon.ico" type="image/x-icon" />
<body>
	<a href="http://thedp.com"><img id="flag" src="../assets/flag.png" alt="The Daily Pennsylvanian" /></a>
	<div class="apps_pageheader">Modify Assignment</div>
	<tr> 
		<td colspan="2"><img src="../assets/red.gif" width="100%" height="2" vspace="3"></td>
	</tr>
	<?php 
	if (isset($_POST['delete'])){
		$query = "DELETE FROM story WHERE storyid = ".$_GET['storyid'];
		run_sql($query);
		echo "The story has sucessfuly been removed.";
	}
	else{
		if (isset($_POST['title'])) {
			processFormResults();
		}
		else {
			displayCreateEventForm();
		}
	}

	function processFormResults() {
		$query = "";
		$time = date('h:i:00 ', mktime($_POST['hour'], $_POST['minute'])).$_POST['meridian'];
		$date = date('Y-m-d', mktime(0, 0, 0, $_POST['month'], $_POST['day'], $_POST['year']));
		//echo $date;
		if (isset($_POST['edit'])) {
			$query = "UPDATE story SET ".
				"title='".addslashes($_POST['title'])."', ".
				"description='".addslashes($_POST['description'])."', ".
				"time='".$time."', ".
				"date='".$date."', ".
				"location='".addslashes($_POST['location'])."' ".
				"WHERE storyid = ".$_GET['storyid'];
		}
		else if (isset($_POST['create'])) {
			$query = "INSERT INTO story (title, description, time, date, location)"
				."VALUES('"
				.addslashes($_POST['title'])."', '"
				.addslashes($_POST['description'])."', '"
				.$time."', '"
				.$date."', '"
				.addslashes($_POST['location'])."')";
		}
		//echo $query;
		run_sql($query);
		?>
		<div align=center>
			<p><?php echo "Your update has been made.";?></p>
		</div>
		<?php
	}

	function displayCreateEventForm() {
		if (isset($_POST['edit'])) {
			$query = "SELECT * FROM story WHERE storyid = " . $_GET['storyid'];
			$result = run_sql($query);
			$storyid = mysql_result($result,0,"storyid");
			$title = mysql_result($result,0,"title");
			$description = mysql_result($result,0,"description");
			$location = mysql_result($result,0,"location");
			$date = strtotime(mysql_result($result,0,'date'));
			$month = date('m', $date);
			$day = date('d', $date);
			$year = date('Y', $date);
			$time = strtotime(mysql_result($result,0,'time'));
			$hour = date('h', $time);
			$minute = date('i', $time);
			$meridian = date('a', $time);
		}
		else if (isset($_POST['create'])) {
			$title = $description = $location = $date = $month = $day = $year = $time = $hour = $minute = $meridian = "";
		}
		?>
			<div class="indent">
			<?php if (isset($_POST['edit'])) {?> <form method="post" action="edit.php?storyid=<?php echo $_GET['storyid']; ?>">
			<?php } else if (isset($_POST['create'])) {?> <form method="post" action="edit.php"> <?php } ?>
			<table>
				<tr>
					<td>Event Name:</td>
					<td>
						<input type="text" name="title" size=50 value="
							<?php echo trim(stripslashes($title)); ?>">
					</td>
				</tr>
				
				<tr>
					<td>Event Description:</td>
					<td>
						<textarea cols=50 rows=5 name="description">
							<?php echo trim(stripslashes($description)); ?>
						</textarea>
					</td>
				</tr>
			
				<tr>
					<td>Event Location:</td>
					<td>
						<input type="text" name="location" size=50 value="
							<?php echo trim(stripslashes($location)); ?>">
					</td>
				</tr>
				
				<tr>
					<td>Event Date:</td>
					<td>
						<?php
							echo month_dropdown('month', $month);
							echo day_dropdown('day', $day);
							echo year_dropdown('year', $year);
						?>
					</td>
				</tr>
				
				<tr>
					<td>Time</td>
					<td>
					<?php
						echo hour_dropdown('hour', $hour);
						echo minute_dropdown('minute', $minute);
						echo meridian_dropdown('meridian', $meridian);
					?>
					</td>
				</tr>
			</table>
			<?php if (isset($_POST['edit'])) {?> <input type=hidden name=edit>
			<?php } else if (isset($_POST['create'])) {?> <input type=hidden name=create> <?php } ?>
			<input type="Submit" value="Submit">
			</form>
		</div>
<?php } ?>
	<p align=center><a href="../index.php">Return to index.</a></p>
</body>