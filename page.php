<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package HSO
 */

get_header();

$exclude_advertiser_disclosure_link = $post->post_name === 'faq' ? false : true;
?>

	<main id="primary" class="site-main">

		<?php
			get_template_part( 'template-parts/breadcrumbs', null, array( 'exclude_advertiser_disclosure_link' => $exclude_advertiser_disclosure_link ) );
			while ( have_posts() ) :
				the_post();

				get_template_part( 'template-parts/content', 'page' );

			endwhile;
		?>

	</main>

<?php
get_footer();
