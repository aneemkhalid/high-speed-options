<?php
/**
 * Template part for displaying provider children in single-provider.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package HSO
 */

$post_slug = $post->post_name;
?>

<article id="provider-<?php the_ID(); ?>" <?php post_class($post_slug.'-provider'); ?>>

	<h1 class="entry-title">
		<?php the_title(); ?>
	</h1>
	<div class="entry-content mt-4">
		<?php the_content(); ?>
	</div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->
