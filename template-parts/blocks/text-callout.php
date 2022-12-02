<?php

/**
 * Add Text Callout Block.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

$callout_title = get_field('callout_title');
$callout_text = get_field('callout_text', false, false);
$callout_style = get_field('callout_style');
if ($callout_style == 'grey-background'){
	$callout_style.=' p-3';
}
$return = '';
if ($callout_title || $callout_text){
	$return .= '
	<div class="text-callout '.$callout_style.'">';
		$return .= ($callout_title) ? '<h3>'.$callout_title.'</h3>' : '';
		$return .= ($callout_text) ? '<p class="mb-0">'.$callout_text.'</p>' : '';	
	$return .= 
	'</div>';
}
echo $return;
