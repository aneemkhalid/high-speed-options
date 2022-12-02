<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package HSO
 */

get_header();
?>

	<main class="site-main">
		<section class="banner">
			<div class="container">
				<?php get_template_part( 'template-parts/breadcrumbs', null, array( 'is_blog' => true ) ); ?>
			</div>
		</section>
		<section class="post-content mt-2">
			<div class="container">
				<?php
				while ( have_posts() ) :
					the_post();

					get_template_part( 'template-parts/content', get_post_type() );

				endwhile; // End of the loop.
				?>
			</div>
		</section>
		
		<?php get_template_part('/template-parts/related_posts', null, ['container' => true]); ?>

	</main><!-- #main -->

<?php
get_footer();
