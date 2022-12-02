<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package HSO
 */

get_header();

if ( have_posts() ) :
	/* Start the Loop */
	while ( have_posts() ) :
		the_post();
	endwhile;
endif;
		
get_footer();
