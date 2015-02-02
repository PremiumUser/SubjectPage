<?php
/**
 * Template Name: Admin template activities
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


<h1>Here you have your timetable</h1>

<?php
global $wpdb;
$usosid = $wpdb->get_var("select identifier from wp_wslusersprofiles where user_id =".intval(get_current_user_id()));
$num = $wpdb->get_var("SELECT COUNT(*) FROM plan_events where UID=".$usosid);
//echo $num." ".$midd;
echo "<table>";
for ($i = 0; $i < $num; $i++) {
	$row = $wpdb->get_row("SELECT * FROM plan_events where UID=".$usosid." ORDER BY begin", ARRAY_N,$i);
	echo "<tr><td><h2>".$row[1]."</h2></td><td><h2>".$row[2]."</h2></td><td><h2>".$row[3]."</h2></td></tr>";
}
echo "</table>";
?>
	
		</div><!-- #content -->
	</div><!-- #primary -->
</div><!-- #main-content -->

<?php
get_sidebar();
get_footer();?>
