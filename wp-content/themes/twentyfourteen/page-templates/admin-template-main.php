<?php
/**
 * Template Name: Admin template main
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
if (is_admin()) {
	get_template_part('content','page');
	echo "<h1>Hello master</h1>";
}
else {
	echo "<h1>You are in the wrong side of town buddy.</h1>";
}
?>
	
		</div><!-- #content -->
	</div><!-- #primary -->
</div><!-- #main-content -->

<?php
get_sidebar();
get_footer();?>
