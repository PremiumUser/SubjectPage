<?php
/**
 * @package My plugin
 * @version 0.0
 */
/*
Plugin Name: Wordpress 'subject home page' provider
Description: This plugin enables you to act as a admin of a subject and makes each other user participant in your subject. As a administrator you have ability to set grades, award badges and add events to the students timeline.
Author: Bart Zak
Version: 0.5
 */
function subject_admin_menu() {
	add_options_page('Subject administrating','Subject administrating','manage_options',ABSPATH.'/wp-content/plugins/plug/subject-administrator.php','');
}

add_action('admin_menu', 'subject_admin_menu');
?> 
