
<?php
date_default_timezone_set('Europe/Warsaw');
$badgeadded = false;
$badgeerror = false;
$testadded = false;
$gradeadded = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	//Checking which fields were overwriten
/*	if (!empty($_POST["badger"])) { //Badge awarding system
		$content = $_POST["badger"];
		echo "<h1>".$content . "</h1>";
		if (!empty($_POST["badgeexp"])) {
			$content = $_POST["badgeexp"];
		}
}*/

	if (!empty($_POST["testr"])) { //Test making system
		global $wpdb;
		$word = $_POST["testr"];
		$wpdb->query("CREATE TABLE tests (name varchar(30));");
		if ($wpdb->get_var("SELECT COUNT(*) FROM tests where name =\"".$word."\"") == 0) {
			$wpdb->insert('tests',array ('name' => $word),
				array ('%s'));
			echo "<h1 style=\"color:#005500\">Test added!</h1>";
		}
	}
	
	if (!empty($_POST["msg"])) {
		global $wpdb;
		$word = $_POST["msg"];
		$wpdb->query("CREATE TABLE messages (UID int, content varchar(250));");
		$num = $wpdb ->get_var("SELECT COUNT(*) FROM users_personal;");
		for ($i = 0; $i < $num; $i++) {
			$row = $wpdb -> get_row("SELECT * FROM users_personal;", ARRAY_N, $i);
			$wpdb->insert('messages', array('UID' => $row[0], 'content' => date('l jS F Y h:i:s A') . " " . $word), array('%d', '%s'));
		}
		echo "<h1 style=\"color:#005500\"> Message succesfully sent! </h1>";
	}
	if (!empty($_POST["badgeuser"])) {
		global $wpdb;
		$user = $_POST["badgeuser"];
		$wpdb->query("CREATE TABLE badges (UID int, content varchar(200));");
		$wpdb->insert('badges', array('UID' => $user, 'content' => $_POST["badgeexp"]), array('%d','%s'));
		echo "<h1 style=\"color:#005500\">Badge given!</h1>";
	}
	if (!empty($_POST["grader"])) {
		global $wpdb;
		$user = $_POST["gradeuser"];
		$grade = $_POST["grader"];
		$testname = $_POST["testgive"];
		$wpdb->query("CREATE TABLE grades (UID int, grade int, testname varchar(30));");
		$wpdb->insert('grades', array('UID' => $user, 'grade' => $grade, 'testname' => $testname), array('%d', '%d', '%s'));
		echo "<h1 style=\"color:#005500\">Grade uploaded!</h1>";
	}
	if (!empty($_POST["eventname"])) {
		//TODO write
		$eventerr = false;
		if (!is_numeric($_POST["eventday"]) || !is_numeric($_POST["eventmonth"]) || !is_numeric($_POST["eventyear"]) || !is_numeric($_POST["starthour"]) || !is_numeric($_POST["startminute"]) || !is_numeric($_POST["endhour"]) || !is_numeric($_POST["endminute"])) { //Not a integer input
			$eventerr = true;
			echo "not integer!";
		}
		$year = intval($_POST["eventyear"]);
		$month = intval($_POST["eventmonth"]);
		$day = intval($_POST["eventday"]);
		$sh = intval($_POST["starthour"]);
		$sm = intval($_POST["startminute"]);
		$eh = intval($_POST["endhour"]);
		$em = intval($_POST["endminute"]);
		//Automatic conversion= yymmddhhmm
		if ($year < 2000 || $year > 2099 || $month < 1 || $month > 12 || $day < 1 || $day > cal_days_in_month(CAL_GREGORIAN, $month, $year) || $sh < 1 || $sh > 24 || $sm < 1 || $sm > 60 || $eh < 1 || $eh > 24 || $em < 1 || $em > 60) {
			$eventerr = true;
			echo "conversion error!";
		}
		$year = strval($year-2000);
		$month = strval($month);
		$day = strval($day);
		$sh = strval($sh);
		$sm = strval($sm);
		$eh = strval($eh);
		$em = strval($em);
		if ($eventerr) {
			echo "<h1 style=\"color:#aa0000\">Invalid input!</h1>";
		} else {
			global $wpdb;
			$wpdb->query("CREATE TABLE plan_events(UID int, name varchar(30), begin timestamp, end timestamp, EID int NOT NULL AUTO_INCREMENT, PRIMARY KEY(EID));");
			$num = $wpdb ->get_var("SELECT COUNT(*) FROM users_personal;");
			for ($i = 0; $i < $num; $i++) {
				$row = $wpdb -> get_row("SELECT * FROM users_personal;", ARRAY_N, $i);
				$wpdb->insert('plan_events', array('UID' => $row[0], 'name' => $_POST["eventname"], 'begin' =>$year . $month . $day . $sh . $sm, 'end'=>$year . $month . $day . $eh . $em), array('%d','%s','%s','%s'));
			}
			echo "<h1 style=\"color:#005500\">Event created.</h1>";
		}
	}
	/*
	if ($_POST["taskcont"]) {
		echo "Why?";
		$word = $_POST["taskcont"];
		$wpdb->query("create table tasks_pending (TID int NOT NULL AUTO_INCREMENT, maker int, content varchar(40))");
		$wpdb->insert('tasks_pending', array('maker' => 0, 'content' => $word), array('%d', '%s'));
	}*/
} 
else {
	echo "<h1>Welcome to the administrating panel.</h1>";
}
?>

<h1> Welcome master! Your minions wait to be administered.</h1>
<?php
$num = $wpdb ->get_var("SELECT COUNT(*) FROM users_personal;");
echo "<table border=\"1\"><tr><td><h2>User id</h2></td><td><h2>Name and surname</h2></td><td><h2>Badges owned</h2></td><td><h2>Grades</h2></td></tr>";
for ($i = 0; $i < $num; $i++) {
	$row = $wpdb -> get_row("SELECT * FROM users_personal;", ARRAY_N, $i);
	$badgenum = $wpdb -> get_var("SELECT COUNT(*) FROM badges WHERE UID =".$row[0]);
	$gradeslist = "";
	$num2 = $wpdb -> get_var("SELECT COUNT(*) FROM grades WHERE UID =".$row[0]);
	for ($j = 0; $j < $num2; $j++) {
		$row2 = $wpdb -> get_row("SELECT * FROM grades WHERE UID =".$row[0], ARRAY_N, $j); 
		$gradeslist = ($gradeslist . $row2[1]. " ");
	}
	echo "<tr><td><h2>".$row[0]."</h2></td><td><h2>".$row[2]." ".$row[1]."</h2></td><td><h2>".$badgenum."</h2></td><td><h2>".$gradeslist."</h2></td></tr>";
}
echo "</table>";
?>
<hr>
<?php //Adding badges for the users?>
<form name="badgeform" action="" method="post" id="badgeform">
	<h1> Here you can add badge for a user. </h1>
	<h2> Add badge to a user:</h2>
	<select name="badgeuser">
	<?php 
	$num = $wpdb->get_var("SELECT COUNT(*) FROM users_personal;");
        for ($i = 0; $i < $num; $i++) {
		$names = $wpdb -> get_row("SELECT * FROM users_personal;", ARRAY_N, $i);
		$name = $names[2] . " " . $names[1];
		echo "<option value=\"" . $names[0] . "\">" . $name . "</option>";
	}
	?>
	</select>
	<br>
	<input type="submit" name="submit" value="Award badge">
</form>
Why do you want this badge to be given? Use under 100 characters.
<br>
<textarea name="badgeexp" rows="3" cols="50" maxlength="100" form="badgeform"></textarea>
<hr>
<?php //Adding test to the database ?>
<h1> Here you can add test to the database.</h1>
<form name="testform" action="" method="post">
	<h2> Add test with the following name: </h2> <input type="text" name="testr" value="Test nr " maxlength=30>
	<input type="submit" name="submit" value="Add test">
</form>

<hr>
<?php //Adding grade to the student ?>
<form name="gradeform" action="" method="post" id="gradeform">
	<h1> Here you can add a grade to a user for a some test.</h1>
	<h2> Add grade:</h2>
	<select name="grader">
	<option value="5" selected="selected">5</option>
	<option value="4">4</option>
	<option value="3">3</option>
	<option value="2">2</option>
	</select>
	<h2> For a user:</h2>
	<select name="gradeuser">
	<?php
	$num = $wpdb->get_var("SELECT COUNT(*) FROM users_personal;");
	for ($i = 0; $i < $num; $i++) {
		$names = $wpdb -> get_row("SELECT * FROM users_personal;", ARRAY_N, $i);
	        $name = $names[2] . " " . $names[1];
	        echo "<option value=\"" . $names[0] . "\">" . $name . "</option>";
	}
	?>
	</select>
	<h2> Add testname (you have 
	<?php 
	echo $wpdb->get_var("SELECT COUNT(*) FROM tests");
	?> tests): </h2>
	
	<select name="testgive">
	<option value="No test grade" selected="selected">No test grade</option>
	<?php
		$num = $wpdb->get_var("SELECT COUNT(*) FROM tests;");
		for ($i = 0; $i < $num; $i++) {
			$tname = $wpdb->get_row("SELECT * FROM tests;",ARRAY_N,$i);
			$name = $tname[0];
			echo "<option value=\"".$name."\">".$name."</option>";
		}
	?>
	</select>
	<input type="submit" name="submit" value="Add grade">
</form>

<hr>
<h1>Here you can send global message. </h1>
<h2> Use under 200 characters. </h2>
<textarea name="msg" form="msgform" rows=6 cols=50 maxlength=200></textarea>
<form name="msgform" action="" method="post" id ="msgform">
	<input type="submit" name="submit" value="Send message">
</form>
<hr>
<h1>Here you can create events for all the users.</h1>
<h2>Choose a title for it:<h2>
<input type="text" form="eventform" name="eventname" value="" maxlength="30">
<br>
<h2>Type date of the event:</h2>
<textarea form="eventform" name="eventday" value="" maxlength="2" cols="2" rows="1">dd</textarea><b>-</b>
<textarea form="eventform" name="eventmonth" value="" maxlength="2" cols="2" rows="1">mm</textarea><b>-</b>
<textarea form="eventform" name="eventyear" value="" maxlength="4" cols="4" rows="1">yyyy</textarea> <br>
<h2>Set the starting hour:</h2>
<textarea form="eventform" name="starthour" value="" maxlength="2" cols="2" rows="1">hh</textarea>
<textarea form="eventform" name="startminute" value="" maxlength="2" cols="2" rows="1">mm</textarea>
<h2>And the ending hour:</h2>
<textarea form="eventform" name="endhour" value="" maxlength="2" cols="2" rows="1">hh</textarea>
<textarea form="eventform" name="endminute" value="" maxlength="2" cols="2" rows="1">mm</textarea>
<form name="eventform" action="" method="post" id="eventform">
	<input type="submit" name="submit" value="Create event">
</form>
