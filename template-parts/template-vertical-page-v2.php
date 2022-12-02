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
 * template Name: Template - Vertical Page v2
 * Template Post Type: internet-pages, tv-pages, bundle-pages
 * @package HSO
 */

get_header();

	while ( have_posts() ) :
		the_post(); 
		require get_theme_file_path( '/template-parts/page-banner.php' );?>

		<section class="vertical_page_template_v2">
			<div class="container">

				<section class="right_service_provider">
					<h1 class="bridge-till-redesign">Find the Right <span><?php the_field('main_title') ?></span></h1>
					<p><?php the_field('description') ?></p>
				</section>

				<?php the_content(); ?>

				<?php get_template_part('/template-parts/faq', null, []); ?>
				
				<?php get_template_part('/template-parts/related_posts'); ?>
			</div>

		</section>

	<?php 		
	endwhile;
get_footer();