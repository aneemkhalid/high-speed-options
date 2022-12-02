<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package HSO
 */

$info = get_field('404', 'options');
$title = $info['title'];
$content = $info['content'];

get_header();
?>
<div class="container">
	<div class="page-not-found">
		<img src="<?php echo get_template_directory_uri() ?>/images/404_graphic.svg" alt="404" width="1000" height="300">
		<div class="page-not-found-content">
			<h1><?php echo $title ?></h1>
			<div><?php echo $content ?></div>
			<a class="cta_btn" href="<?php echo site_url(); ?>">Go to Home</a>
		</div>
	</div>
</div>

<?php
get_footer();
