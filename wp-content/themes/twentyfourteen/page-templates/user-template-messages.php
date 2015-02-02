<?php
/**
 * Template Name: Admin template messages
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
$usosid = $wpdb->get_var("select identifier from wp_wslusersprofiles where user_id =".intval(get_current_user_id()));
//echo $usosid;
//?>

<?php
$num = $wpdb->get_var("select count(*) from messages where UID =".$usosid);
for ($i = 0; $i < $num; $i++) {
	if ($_POST["submit".strval($i)]) {
		//echo "<h3>submit number ".$i." clicked!</h3>";
		$content = $wpdb->get_row("select content from messages where UID =".$usosid." ORDER BY content",ARRAY_N,$i);
		$wpdb->query("DELETE FROM messages WHERE UID = ".$usosid." AND content =\"".$content[0]."\"");
	}
}
?>

<h1>Welcome to your mailbox!</h1>
<?php
$num = $wpdb->get_var("select count(*) from messages where UID =".$usosid);

if ($num == 0) {
	echo "<h2>You have no unhandled messages in the messages box</h2>";
	echo "<h2>You are keepin' it tidy. Good ^^</h2>";
} else {
	echo "<h2>You have ".$num." unhandled messages.</h2><hr>";
	for ($i = 0; $i < $num; $i++) {
		$arr = $wpdb->get_row("select * from messages where UID =".$usosid." ORDER BY content", ARRAY_N, $i);
		echo "<form name =\"form".$i."\" action=\"\" method=\"post\">";
		echo "<h3 style=\"color:#000022\">".$arr[1]."</h3>";

		echo "<input type=\"submit\" name=\"submit".$i."\" value=\"Erase this message\">";
		echo "</form><hr>";
	}
}
?>
	
		</div><!-- #content -->
	</div><!-- #primary -->
</div><!-- #main-content -->

<?php
get_sidebar();
get_footer();?>
