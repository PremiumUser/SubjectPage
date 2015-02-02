<?php
/**
 * Template Name: User functionalities
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>

<div id="main-content" class="main-content">

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
<h1>Here are all the goodies for usos-logged users. ;)</h1>
<?php //TODO ALERTS!
$usosid = $wpdb->get_var("select identifier from wp_wslusersprofiles where user_id =".intval(get_current_user_id()));
//echo $usosid;
?>
<hr>
<?php 
$num = $wpdb->get_var("select count(*) from badges where UID=".$usosid);
if ($num == 0) {
	echo "<h2>You have no badges. :( </h2>";
} else {
	if ($num == 1){
		echo "<h2>You have 1 badge. :)";
	} else {
		echo "<h2>You have ".$num." badges. :D";
	}
	echo "<ul>";
	for ($i=0; $i < $num; $i++) {
		$arr = $wpdb->get_row("select * from badges where UID=".$usosid,ARRAY_N,$i);
		echo "<li><h3>Badge nr ".($i+1).": ";
		echo $arr[1]." ";
		echo "</h3></li>";
	}
	echo "</ul>";
}
?>
<hr>
<h2>Here are your grades:</h2>
<?php
$num = $wpdb->get_var("select count(*) from grades where UID=".$usosid);
echo "<h5>";
for ($i=0; $i < $num; $i++) {
	$arr =  $wpdb->get_row("select * from grades where UID=".$usosid,ARRAY_N,$i);
	echo $arr[1]." from the test: ".$arr[2];
	if ($i + 1 < $num) {
		echo ", ";
	} else {
		echo ".";
	}
}
?>
<h2>You can also check what are peoples grades from different tests:</h2>

<form name="showtests" action="" method="post">
<select name="testoption">
<?php
$num = $wpdb->get_var("select count(*) from tests;");
for ($i=0; $i<$num; $i++) {
	$arr = $wpdb->get_row("select name from tests",ARRAY_N,$i);
	$check = $wpdb->get_var("select count(*) from grades where UID=".$usosid." AND testname = \"" .$arr[0]."\"");
	if ($check > 0)
		echo "<option value=\"".$arr[0]."\">".$arr[0]."</option>";
}
?>
</select>
<input type="submit" name="submit" value="Show me!">
</form>
<?php
if ($_POST["testoption"]) {
	$test = $_POST["testoption"];
	$num = $wpdb->get_var("select count(*) from grades where testname =\"".$test."\"");
	echo "<table style =\"width:50%\" align=\"center\">";
	echo "<tr><td><h2>".$test."</h2></td><td><h2>GRADE</h2></td></tr>";
	for ($i = 0; $i < $num; $i++) {
		echo "<tr>";
		$arr = $wpdb->get_row("select * from grades where testname =\"".$test."\"",ARRAY_N,$i);
		
		echo "<td><h3>".$arr[0];
		if ($arr[0] == $usosid)
			echo " (you) ";
		echo "</h3></td><td><h3>".$arr[1]."</h3></td>";

		echo "</tr>";
	}
	echo "</table>";	
}
?>
		</div><!-- #content -->
	</div><!-- #primary -->
</div><!-- #main-content -->

<?php
get_sidebar();
get_footer();?>
