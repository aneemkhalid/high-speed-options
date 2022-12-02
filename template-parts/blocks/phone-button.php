<?php

/**
 * Add Phone Button Block.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

$provider_id = get_field('provider');
if ($provider_id){
	// $provider = get_post($provider_id);
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
					$cta_text = 'View Plans';
					$cta_link = $camp['digital_tracking_link'];
				}
			}
			
		}			
	}else{
		$cta_text = 'View Plans';
		$cta_link = get_field('brands_website_url', $provider_id);
	}
}
if(!empty($cta_text)) echo '<div class="d-flex justify-content-center"><a href="'.$cta_link.'" class="cta_btn" target="_blank">'.$cta_text.'</a></div>'; ?>
