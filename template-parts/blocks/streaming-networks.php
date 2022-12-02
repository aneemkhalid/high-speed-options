<?php

/**
 * Comparison Tabel Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

$main_heading = get_field('main_heading');
$networks = get_field('networks');
?>
<div class="alternatives">
	<h3>Top 5 Streaming Networks with Live TV</h3>
	<div class="streaming-networks">
		<div class="network">
			<img src="<?php echo get_template_directory_uri() ?>/images/youtube-tv.jpg" alt="Youtube.jpg">
			<p>YouTube TV</p>
		</div>
		<div class="network">
			<img src="<?php echo get_template_directory_uri() ?>/images/sling.png" alt="Sling">
			<p>Sling TV</p>
		</div>
		<div class="network">
			<img src="<?php echo get_template_directory_uri() ?>/images/hulu.svg" alt="Hulu">
			<p>Hulu + Live TV</p>
		</div>
		<div class="network">
			<img src="<?php echo get_template_directory_uri() ?>/images/att_tvnow.jpg" alt="AT&T">
			<p>AT&T TV Now</p>
		</div>
		<div class="network">
			<img src="<?php echo get_template_directory_uri() ?>/images/philo.png" alt="Philo">
			<p>Philo</p>
		</div>
	</div>
</div>