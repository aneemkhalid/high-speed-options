<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package HSO
 */

$post_slug = $post->post_name;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class($post_slug.'-page'); ?>>

	<h1 class="entry-title">
		<?php the_title(); ?>
	</h1>

	<?php hso_post_thumbnail(); ?>

	<div class="entry-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->
