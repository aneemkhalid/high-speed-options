<?php

/**
 * Provider Card Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

$provider_type = get_field('provider_type');
if ($provider_type == 'telecom'){

	$provider_id = get_field('provider');

}elseif ($provider_type == 'streaming'){

	$provider_id = get_field('streaming_provider');
}
if ($provider_id):

	$logo = get_field('logo', $provider_id);

	$zip_popup_class = $datalayer = $show_plan = $datalayer = $data_att = $target = $target2 = $cta_link2 = $cta_text2='';

	if ($provider_type == 'telecom'):

		$provider_link = get_post_permalink( $provider_id );
		$prod_cat = 'Telecom Provider';
		$internet_checked = $tv_checked = $bundle_checked = '';
		$partner = get_field('partner', $provider_id);
		$show_plan = get_field('show_plan_information');
		$provider_features = get_field('main_features', $provider_id);
		$title = get_the_title($provider_id);
		$button_type = get_field('provider_card_button_type');

		$prov_variant = [
			'text' => 'Provider Card'
		];
		if ($button_type == 'link'){
			if($partner){
				$buyer_id = get_field('buyer', $provider_id);
				$campaign = get_field( "campaign", $buyer_id );
				foreach($campaign as $key => $camp) {
					$type_of_partnership = $camp['type_of_partnership'];

					if($camp['campaign_name'] == $provider_id){
						if($type_of_partnership == 'call_center'){
		                    $cta_text = '<span class="material-icons">call</span>'.$camp['call_center'];
		                    $cta_link = 'tel:'.$camp['call_center'];
		            
		                }elseif($type_of_partnership == 'digital_link'){
		                    $cta_text = 'Order Online';
		                    $cta_link = $camp['digital_tracking_link'];
		                    $target='target="_blank"';
		                } else {

		                    if ($camp['primary_conversion_method'] == 'call_center'){
		                        $cta_text = '<span class="material-icons">call</span>'.$camp['call_center'];
		                        $cta_text2 = '<p class="mt-2 mb-0 tel-link font-weight-bold">Order Online</p>';
		                        $cta_link = 'tel:'.$camp['call_center'];
		                        $cta_link2 = $camp['digital_tracking_link'];
		                        $target2='target="_blank"';
		                    } else {
		                        $cta_text = 'Order Online';
		                        $cta_text2 = '<p class="mt-2 mb-0"><span class="small-text">Call to order:</span><span class="tel-link font-weight-bold"> '.$camp['call_center'].'</span></p>';
		                        $cta_link = $camp['digital_tracking_link'];
		                        $cta_link2 = 'tel:'.$camp['call_center'];
		                        $target='target="_blank"';
		                    }
		                }
		            }
		        }
					
			}else{
				$cta_text = 'View Plans';
				$cta_link = get_field('brands_website_url',$provider_id);
				$title = get_field('name',$provider_id);
			}
			$datalayer = dataLayerOutboundLinkClick( $provider_id, $prod_cat, $cta_link );

			if ($cta_link2){
				$datalayer2 = dataLayerOutboundLinkClick( $provider_id, $prod_cat, $cta_link2 );
		    }
		}elseif($button_type == 'popup'){
			$cta_text = get_field('provider_card_button_text');
			$cta_link = '#';
			$rand = rand();
			$data_att = 'data-toggle="modal" data-target="#zipPopupModal-'.$rand.'"';
			$zip_popup_class = 'zip-popup-btn';
			$default_tab = get_field('provider_card_default_tab');
			if ($default_tab == 'internet'){
				$internet_checked = 'checked';
			} elseif ($default_tab == 'tv'){
				$tv_checked = 'checked';
			} elseif ($default_tab == 'bundle'){
				$bundle_checked = 'checked';
			}

			$datalayer = dataLayerCheckAvailabilityClick($provider_id, $post_tags);

			require get_theme_file_path( '/template-parts/zip-search-popup.php' );
		}
		$provider_datalayer = dataLayerProdClick($provider_id, $prov_variant, 0,  $prod_cat, 'Provider Card');

	elseif($provider_type == 'streaming'):
	
		$provider_features = get_field('provider_features');
		$cta_text = 'View Plans';
		$cta_link = get_field('link', $provider_id);
		$prod_cat = 'Streaming Provider';
		$provider_link = $cta_link;
		$datalayer = dataLayerOutboundLinkClick( $provider_id, $prod_cat, $cta_link );
		$provider_datalayer = $datalayer;


	endif;	

	?>
	<section class="features-card">
		<div class="container">
			<div class="inner">
				<div class="img_wrap col-md-3 col-sm-12 text-center text-md-left">
					<?php if($logo): ?>
						<a href="<?php echo $provider_link ?>" onclick="<?php echo $provider_datalayer; ?>">
							<img src="<?php echo $logo ?>" alt="<?php echo get_the_title() ?>">
						</a>
					<?php endif; ?>
				</div>
				<div class="features-list">
				<?php
					if( $provider_features ) {
					    echo '<ul>';
					    foreach( $provider_features as $feature ) {
							echo '<li>'.$feature['feature'].'</li>';
					    }
					    echo '</ul>';
					}
				?>
				</div>
				<div class="text-center button-container">
				<?php if(!empty($cta_text)) echo '<a href="'.$cta_link.'" onclick=" ' . $datalayer .' " class="cta_btn '.$zip_popup_class.'" '.$target.' '.$data_att.'>'.$cta_text.'</a>'; ?>
				<?php if(!empty($cta_text2)) echo '<a href="'.$cta_link2.'" onclick=" ' . $datalayer2 .' " '.$target2.'>'.$cta_text2.'</a>'; ?>
				<?php if($show_plan) : ?><a href="<?php echo $provider_link ?>#plans" class="plan-info" onclick="<?php echo $provider_datalayer; ?>">VIEW PLAN INFORMATION</a><?php endif; ?>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>