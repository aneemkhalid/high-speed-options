<?php

/**
 * Add Featured Quote Block.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

$quote = get_field('quote');
$author_first_name = get_field('author_first_name');
$author_last_name = get_field('author_last_name');
$source = get_field('source');
$return = '';
if ($quote){
	$return .= '
	<div class="featured-quote d-flex">
		<span class="material-icons format-quote mr-4">format_quote</span>
		<blockquote class="quote-text-wrapper">
			<p class="quote mb-1">'.$quote.'</p>
			<p class="cite mb-0">'.$author_first_name.' '.$author_last_name;
			$return .= ($source) ? ', '.$source : '';
			$return .= '</p>
		</blockquote>	
	</div>';
}
echo $return;
