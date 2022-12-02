<?php
/**
 * The template for displaying all single locations
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package HSO
 */

// Check if city or state
$id = get_the_ID();
$loc_type = wp_get_post_terms($id, 'location_type');
$loc_type = $loc_type[0]->slug;
//print_r($loc_type);

if($loc_type == 'state') {
	include get_template_directory() . '/template-parts/template-state.php'; 
}
else {
	include get_template_directory() . '/template-parts/template-city.php'; 
}

