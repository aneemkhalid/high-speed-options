<?php

/**
 * Comparison Callout Element Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
$callout_text = get_field('callout_text');
if($callout_text){
	echo '<div class="callout-text"><p>'.$callout_text.'</p></div>';
}
?>

