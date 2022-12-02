<?php

/**
 * Add HSO Button Block.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
$hso_button = get_field('hso_button');
$button_type = $hso_button['button_type'];
$data_att = '';
$button_link = '#';
$button_target = '';
$internet_checked = '';
$tv_checked = '';
$bundle_checked = '';
$zip_popup_class = '';
$button_align = 'center';
if ($hso_button['button_align']){
	$button_align = $hso_button['button_align'];
}
if ($button_type == 'popup'){
	$rand = rand();
	$data_att = 'data-toggle="modal" data-target="#zipPopupModal-'.$rand.'"';
	$zip_popup_class = 'zip-popup-btn';
	$button_title = $hso_button['button_text'];
	$default_tab = $hso_button['default_tab'];
	if ($default_tab == 'internet'){
		$internet_checked = 'checked';
	} elseif ($default_tab == 'tv'){
		$tv_checked = 'checked';
	} elseif ($default_tab == 'bundle'){
		$bundle_checked = 'checked';
	}
	require get_theme_file_path( '/template-parts/zip-search-popup.php' );
} elseif ($button_type == 'link'){
	$button_title = $hso_button['button_text_link']['title'];
	$button_link = $hso_button['button_text_link']['url'];
	$button_target = $hso_button['button_text_link']['target'];
}

if(!empty($button_title)) echo '<div class="d-flex justify-content-'.$button_align.'"><a href="'.$button_link.'" class="cta_btn '.$zip_popup_class.'" target="'.$button_target.'" '.$data_att.'>'.$button_title.'</a></div>'; ?>
