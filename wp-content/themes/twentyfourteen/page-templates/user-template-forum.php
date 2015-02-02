<?php
/**
 * Template Name: User template forum
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>

<div id="main-content" class="main-content">

<?php
	if ( is_front_page() && twentyfourteen_has_featured_posts() ) {
		// Include the featured content template.
		get_template_part( 'featured-content' );
	}
?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
<?php
if ($_POST["tname"]) {
	$word = $_POST["tname"];
	if ($wpdb->get_var("select COUNT(*) from forum_threads where thread =\"".$word."\"") == 0) {
                $wpdb->insert('forum_threads',array('thread' => $word),array('%s'));
	}
}
?>
<h1>Welcome user! Here is your forum, that can be accessed only by students! Share experience and talk!</h1>
<?php
$usosid = $wpdb->get_var("select identifier from wp_wslusersprofiles where user_id =".intval(get_current_user_id()));
$wpdb->query("Create table forum (thread varchar(30), content varchar(1100));");
$wpdb->query("Create table forum_threads(thread varchar(30));");
if ($usosid != null) {//FROM NOW ON Content visible only for 
?>
<h2>Here you can start a thread. If you want then give it a name:</h2>
<textarea name="tname" form="threadcreate" rows="1" cols="30" maxlength="30"></textarea> 
<form name="threadcreate" action="" method="post" id="threadcreate">
<input type="submit" name="submit" value="Create thread">
</form>
<hr>
<h2>Here you can view a existing thread.</h2>
<form name="threadview" action="" method="post" id ="threadview">
<?php
	if ($_POST["submit1"])
		echo "<input type=\"hidden\" name=\"prevthread\" value=\"".$_POST["whichthread"]."\">";
	else
		echo "<input type=\"hidden\" name=\"prevthread\" value=\"\">";
?>
<select name="whichthread">
<?php
$currthread;
if ($_POST["submit1"]) {
	$currthread=$_POST["whichthread"];
}
if ($_POST["submit2"]) {
	$currthread=$_POST["prevthread"];
}
$num = $wpdb->get_var("SELECT COUNT(*) from forum_threads");
for ($i = 0; $i < $num; $i++) {
	$row = $wpdb->get_row("SELECT * from forum_threads", ARRAY_N, $i);
	if ($row[0] == $currthread)
		echo "<option name=\"".$row[0]."\" selected = \"selected\">".$row[0]."<h2>";
	else
		echo "<option name=\"".$row[0]."\">".$row[0]."<h2>";

}
?>
</select>
<input type="submit" name="submit1" value="Search for thread">
</form>
<?php
/*
if ($_POST["tname"]) {
	$word = $_POST["tname"];
	if ($wpdb->get_var("select COUNT(*) from forum_threads where thread =\"".$word."\"") == 0) {
		$wpdb->insert('forum_threads',array('thread' => $word),array('%s'));
	}
}*/

if ($_POST["submit2"]) {
	$myid = $wpdb->get_var("SELECT display_name FROM wp_users where id=".get_current_user_id());
	$wpdb->insert('forum',array('thread' => $_POST["prevthread"], 'content' =>  "User ".$myid."(".$usosid.") wrote: ".$_POST["reply"]), array('%s','%s'));
	$_POST["whichthread"] = $_POST["prevthread"];
}

if ($_POST["submit1"] || $_POST["submit2"]) {
	echo "<textarea name=\"reply\" rows=\"6\" cols=\"200\" maxlength=\"1000\" form=\"threadview\"></textarea>";//textbox
	echo "<input type=\"submit\" name=\"submit2\" value=\"Add reply\" form=\"threadview\">";
	echo "<hr>";
	$thr = $_POST["whichthread"];
	$num = $wpdb->get_var("SELECT COUNT(*) FROM forum where thread = \"".$thr."\"");
	for ($i = $num-1; $i >= 0; $i--) {
		$row = $wpdb->get_row("SELECT content FROM forum where thread =\"".$thr."\"",ARRAY_N,$i);
		echo "<hr><h3>".$row[0] ."<h3>";
	}
	$threadviewednow = $thr;
}

?>
<?php }?>
		</div><!-- #content -->
	</div><!-- #primary -->
</div><!-- #main-content -->

<?php
get_sidebar();
get_footer();?>
