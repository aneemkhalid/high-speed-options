<?php

/**
 * Comparison Tabel Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

$image = get_field('image');
$heading = get_field('heading');
$description = get_field('description');
$left_side_feature_list = get_field('left_side_feature_list');
$right_side_feature_list = get_field('right_side_feature_list');
$hso_button = get_field('hso_button');

$button_type = $hso_button['button_type'];
$button_intro = $hso_button['button_intro'];

$data_att = '';
$button_link = '#';
$button_target = '';
$internet_checked = '';
$tv_checked = '';
$bundle_checked = '';
$zip_popup_class = '';
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
$button_element = '';
if(!empty($button_title)) $button_element = '<div class="zip-popup-intro">'.$button_intro.'</div><div class="d-flex"><a href="'.$button_link.'" class="cta_btn '.$zip_popup_class.'" target="'.$button_target.'" '.$data_att.'>'.$button_title.'</a></div>';

?>

<section class="section-gray row-full">
	<div class="container">
		<div class="topic-spotlight-container">
			<div class="topic-spotlight-heading-container">
				<h2><?php echo $heading; ?></h2>
			</div>
			<div class="topic-spotlight-description-container">
				<?php echo $description; ?>
			</div>
			<div class="topic-spotlight-left-features-container">
				<?php
					if($left_side_feature_list['left_side_feature_list']){
						echo '<div class="features-list">';
						echo '<h5>'.$left_side_feature_list['heading'].'</h5>';
						if($left_side_feature_list['features']){
							echo '<ul>';
							foreach ($left_side_feature_list['features'] as $key => $feature) {
								echo '<li>'.$feature['feature'].'</li>';
							}
							echo '</ul>';
							echo '</div>';
						}
					}
				?>
			</div>
			<div class="topic-spotlight-right-features-container">
				<?php
					if($right_side_feature_list['right_side_feature_list']){
						echo '<div class="features-list">';
						echo '<h5>'.$right_side_feature_list['heading'].'</h5>';
						if($right_side_feature_list['features']){
							echo '<ul>';
							foreach ($right_side_feature_list['features'] as $key => $feature) {
								echo '<li>'.$feature['feature'].'</li>';
							}
							echo '</ul>';
							echo '</div>';
						}
					}
				?>
			</div>
			<div class="topic-spotlight-zip-search-container">
				<?php echo $button_element; ?>
			</div>
			<div class="topic-spotlight-image-container">
				<?php if(!empty($image)) echo '<img src="'.$image.'" alt="'.$heading.'">'; ?>
			</div>
		</div>
	</div>
</section>
