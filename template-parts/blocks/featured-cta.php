<?php

/**
 * Add Featured CTA Block.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
$icon = get_field('featured_cta_icon');
$icon_img = '';
$has_img = '';
if ($icon === 'internet'){
	$icon_img = '<img class="mb-4 mb-sm-0" src="'.get_template_directory_uri().'/images/internet-grey.svg" alt="Internet" height="100" width="100">';
	$has_img = 'has-img';
} elseif ($icon === 'tv_streaming'){
	$icon_img = '<img class="mb-4 mb-sm-0" src="'.get_template_directory_uri().'/images/tv-grey.svg" alt="TV" height="100" width="100">';
	$has_img = 'has-img';
} elseif ($icon === 'bundle'){
	$icon_img = '<img class="mb-4 mb-sm-0" src="'.get_template_directory_uri().'/images/bundles-grey.svg" alt="Bundles" height="100" width="100">';
	$has_img = 'has-img';
}
$title = get_field('featured_cta_title');
$text = get_field('featured_cta_text');
$cta_text = '';
$cta_link = '#';
$data_att = '';
$internet_checked = '';
$tv_checked = '';
$bundle_checked = '';
$zip_popup_class = '';
$provider_type = get_field('provider_type');
if ($provider_type === 'streaming'){
	$provider_id = get_field('streaming_provider');
	if ($provider_id){
		$cta_link = get_field('link', $provider_id);
		if ($cta_link){
			$cta_text = 'View Plans';
		}
	}
} elseif ($provider_type === 'internet_tv'){
	$provider_id = get_field('internet_tv_provider');
	if ($provider_id){
		$partner = get_field('partner', $provider_id);
		if($partner){
			$buyer_id = get_field('buyer', $provider_id);
			$campaign = get_field( "campaign", $buyer_id );
			foreach($campaign as $key => $camp) {
				$type_of_partnership = $camp['type_of_partnership'];
				if($camp['campaign_name'] == $provider_id){
					if($type_of_partnership == 'call_center'){
						$cta_text = '<span class="material-icons">call</span>'.$camp['call_center'];
						$cta_text2 = $camp['call_center'];
						$cta_link = 'tel:'.$camp['call_center'];
						$cta_link2 = 'tel:'.$camp['call_center'];
					}else{
						$cta_text = 'Order Online';
						$cta_link = $camp['digital_tracking_link'];
					}
				}
				
			}			
		}else{
			$cta_text = 'View Plans';
			$cta_link = get_field('brands_website_url', $provider_id);
		}
	}
}
$button_type = get_field('featured_cta_button_type');
if ($button_type == 'popup'){
	$cta_text = get_field('featured_cta_button_text');
	$cta_link = '#';
	$rand = rand();
	$data_att = 'data-toggle="modal" data-target="#zipPopupModal-'.$rand.'"';
	$zip_popup_class = 'zip-popup-btn';
	$default_tab = get_field('featured_cta_default_tab');
	if ($default_tab == 'internet'){
		$internet_checked = 'checked';
	} elseif ($default_tab == 'tv'){
		$tv_checked = 'checked';
	} elseif ($default_tab == 'bundle'){
		$bundle_checked = 'checked';
	}
	require get_theme_file_path( '/template-parts/zip-search-popup.php' );
}
$source = get_field('source');

$cta_btn = ($cta_text && $cta_link) ? '<div class="d-flex justify-content-center justify-content-sm-start"><a href="'.$cta_link.'" class="cta_btn '.$zip_popup_class.'" target="_blank" '.$data_att.'>'.$cta_text.'</a></div>' : '';

$return = '
<div class="featured-cta-wrapper">
	<div class="featured-cta d-flex flex-column flex-sm-row align-items-center align-items-sm-start">';
		$return .= $icon_img;
		$return .= '<div class="cta-text-wrapper '.$has_img.'">';
			$return .= ($title) ? '<h3>'.$title.'</h3>' : '';
			$return .= ($text) ? '<p>'.$text.'</p>' : '';
			$return .= $cta_btn;
		$return .= '</div>';		
	$return .= 
	'</div>';
	if ($source){
		$return .= '<figcaption class="figcaption-source">'.$source.'</figcaption>';
	}
$return .= '</div>';	
echo $return;
