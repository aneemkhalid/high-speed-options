<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package HSO
 */

get_header();
	while ( have_posts() ) :
		the_post(); 
		 if( property_exists($post, 'post_parent') && $post->post_parent): ?>
            <main id="primary" class="site-main">
				<?php
					get_template_part( 'template-parts/breadcrumbs', null, array( 'exclude_advertiser_disclosure_link' => true ) );
					get_template_part( 'template-parts/content', 'provider-child' );
				?>
			</main>
		<?php	
		else:
			get_template_part( 'template-parts/content', 'provider' );
		endif;
	endwhile; // End of the loop.
get_footer();