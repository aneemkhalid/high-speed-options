<?php 
    use ZipSearch\ProviderSearchController as ProviderSearchController;
    use ZipSearch\BDAPIConnection as BDAPIConnection;
    use ZipSearch\ProvidersDBConnection as ProvidersDBConnection;
?>

<div class="tab-content" id="typeTabContent">

	<div class="tab-pane fade <?php echo $internet_active.' '.$internet_show; ?> internet-search" id="internet-search" role="tabpanel" aria-labelledby="internet-search-tab">
		<?php 
		$highest_download_speed = $highest_download_speed_provider = '';
		if (empty($results_arr['internet'])): 
			$highest_download_speed_arr = (new ProvidersDBConnection())->getHighestDownloadSpeedWithProvider($zip_arr);
			if (!empty($highest_download_speed_arr)){
				$highest_download_speed = $highest_download_speed_arr->max_downstream_speed;
				$highest_download_speed_provider = $highest_download_speed_arr->hso_provider;
			}
			?>
			<div class="container">
				<h2 class="no-results-header">No Results found for "<?php echo $no_results; ?>"</h2>
				<p class="demote"> Try searching again using different service type or zip code. </p>
			</div>
			<div style="display:none;" class="highest-download-speed"><?php echo $highest_download_speed; ?></div>
			<div style="display:none;" class="highest-download-speed-provider"><?php echo $highest_download_speed_provider; ?></div>
           <script> function zipSearchDataInternetWrapper() { } </script>
			<?php 
		else:	
				$download_speed_arr_col = array_column($results_arr['internet'], 'download_speed');
				$download_speed_arr_col = array_search(max($download_speed_arr_col), $download_speed_arr_col);
				$highest_download_speed = $results_arr['internet'][$download_speed_arr_col]['download_speed'];
				$highest_download_speed_provider = $results_arr['internet'][$download_speed_arr_col]['name'];

				?>
					<div style="display:none;" class="highest-download-speed"><?php echo $highest_download_speed; ?></div>
					<div style="display:none;" class="highest-download-speed-provider"><?php echo $highest_download_speed_provider; ?></div>

				<?php
				foreach($results_arr['internet'] as $provider): 

					$logo = get_field('logo', $provider['id']);
					$download_speed = $provider['download_speed'];
					$ds_progress_bar_color = '';
					$upload_speed = $provider['upload_speed'];
					$us_progress_bar_color = '';
					$internet_info = get_field('internet', $provider['id']);
					$buyer_id = get_field('buyer', $provider['id']);
					$campaigns = get_field('campaign', $buyer_id);
					$campaign_key = array_search (  $provider['id'], array_column($campaigns, 'campaign_name') );
					$partnership_type = $campaigns[$campaign_key]['type_of_partnership'];
                    $has_plans = get_field('show_plans_page', $provider['id']);
                    $partner = get_field('partner', $provider['id']);
                    $cta_link2 = '';
					$default_na_text = '<span class="na-text-zip-search">' . $internet_info['default_not_available_text'] . '</span>';
					$show_price_asterisk_zipsearch = $internet_info['show_asterisk_price_zip_search_results'] ? '<span class="small-text price-asterisk">*</span>' : '';
					$show_download_asterisk_zipsearch = $internet_info['show_asterisk_download_zip_search_results'] ? '<span class="small-text price-asterisk">*</span>' : '';
					$show_upload_asterisk_zipsearch = $internet_info['show_asterisk_Upload_zip_search_results'] ? '<span class="small-text price-asterisk">*</span>' : '';
					// $asterisk_zipsearch = $show_asterisk_zipsearch ? '<span class="small-text price-asterisk">*</span>' : '';
					
					if (isset($provider['connection_type']) && $provider['connection_type'] != NULL){
						switch ($provider['connection_type']) {
	                        case 'Cable':
	                            $internet_plan_highlights = $internet_info['cable_connection']['cable_plan_highlights'];
	                            $internet_terms_conditions = $internet_info['cable_connection']['cable_terms_&_conditions'];
	                            break;
	                        case 'Fiber':
	                            $internet_plan_highlights = $internet_info['fiber_connection']['fiber_plan_highlights'];
	                            $internet_terms_conditions = $internet_info['fiber_connection']['fiber_terms_&_conditions'];
	                            break;
	                        case 'DSL':
	                            $internet_plan_highlights = $internet_info['dsl_connection']['dsl_plan_highlights'];
	                            $internet_terms_conditions = $internet_info['dsl_connection']['dsl_terms_&_conditions'];
	                            break;
	                        case 'Satellite':
	                            $internet_plan_highlights = $internet_info['satellite_connection']['satellite_plan_highlights'];
	                            $internet_terms_conditions = $internet_info['satellite_connection']['satellite_terms_&_conditions'];
	                            break;
	                        default:
		                        $internet_plan_highlights = $internet_info['plan_highlights'];
								$internet_terms_conditions = $internet_info['terms_&_conditions'];
	                    }
					} else {
						$internet_plan_highlights = $internet_info['plan_highlights'];
						$internet_terms_conditions = $internet_info['terms_&_conditions'];
					}      
					if ($internet_plan_highlights == ''){
						$internet_plan_highlights = $internet_info['plan_highlights'];
					}
					if ($internet_terms_conditions == ''){
						$internet_terms_conditions = $internet_info['terms_&_conditions'];
					}

	                if($partner){
						foreach($campaigns as $key => $camp) {
							$type_of_partnership = $camp['type_of_partnership'];

							if($camp['campaign_name'] == $provider['id']){
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
				                        $cta_text2 = '<p class="mt-2 mb-0 tel-link font-weight-bold text-center">Order Online</p>';
				                        $cta_link = 'tel:'.$camp['call_center'];
				                        $cta_link2 = $camp['digital_tracking_link'];
				                        $target2='target="_blank"';
				                    } else {
				                        $cta_text = 'Order Online';
				                        $cta_text2 = '<p class="mt-2 mb-0 text-center small-text">Call to order:<span class="tel-link font-weight-bold"> '.$camp['call_center'].'</span></p>';
				                        $cta_link = $camp['digital_tracking_link'];
				                        $cta_link2 = 'tel:'.$camp['call_center'];
				                        $target='target="_blank"';
				                    }
				                }
				            }
				        }
							
					}else{
						$cta_text = 'View Plans';
						$cta_link = get_field('brands_website_url',$provider['id']);
					}


	                $counterInternet++;
	  
	                $zipSearchInternetIndv = zipSearchLoadIndv($provider, $zipSearchInternetVariant, $counterInternet, "Internet");
	 
	                $zipSearchInternet .= $zipSearchInternetIndv;        
	        
	                $zipSearchInternetProdClick = zipSearchProdClick($zipcode, $provider, $zipSearchInternetVariant, $counterInternet, "Internet");

	                $zipSearchInternetOutboundClick = dataLayerOutboundLinkClick( $provider['id'], "Internet", $cta_link );
	        		
	        		if ($cta_link2){
	        			$zipSearchInternetOutboundClick2 = dataLayerOutboundLinkClick( $provider['id'], "Internet", $cta_link2 );
	        		}

					?>
				<div class="container zip-container" data-download="<?php echo $download_speed ?>" data-upload="<?php echo $upload_speed ?>" data-cost="<?php echo $provider['cost'] ?>">
				<div class="row provider-box-row">
					<div class="col-xl col-12 provider_box img-box div-rt-lg div-rt-md div-btm-img">
						<div class="img-wrap">
							<a href="<?php echo get_permalink($provider['id']); ?>" onClick="<?php echo $zipSearchInternetProdClick ?>">
								<img src="<?php echo $logo;  ?>" alt="<?php echo get_the_title($provider['id']) ?>" height="50" width="127">
							</a>
						</div>
					</div>
					<div class="col-xl col-12 provider_box div-rt-lg div-btm-lf">
						<p>Starting at</p>
						<?php if($provider['cost'] != 'N/A'): ?>
						<p class="large-text">$<?php echo $provider['cost']; ?>/mo<?php echo $show_price_asterisk_zipsearch; ?></p>
						<?php else: ?>
						<p class="small-text font-weight-bold"><?php echo $default_na_text; ?></p>
						<?php endif; ?>
					</div>
					<div class="col-xl col-12 provider_box div-rt-lg div-rt-md div-btm-lf">
						<p>Max Download</p>
						<?php if($download_speed != 'N/A'): ?>
						<p class="large-text"><?php echo $download_speed; ?> Mbps<?php echo $show_download_asterisk_zipsearch; ?></p>
						<?php else: ?>
						<p class="small-text font-weight-bold"><?php echo $default_na_text; ?></p>
						<?php endif; ?>
					</div>
					<div class="col-lg col-12 provider_box div-rt-lg div-rt-md div-btm-lf">
						<p>Max Upload</p>
						<?php if($upload_speed != 'N/A'): ?>
						<p class="large-text"><?php echo $upload_speed; ?> Mbps<?php echo $show_upload_asterisk_zipsearch; ?></p>
						<?php else: ?>
						<p class="small-text font-weight-bold"><?php echo $default_na_text; ?></p>
						<?php endif; ?>
					</div>
					<div class="col-lg-3 provider_box plan_link">
						<a href="<?php echo $cta_link; ?>" class="cta_btn" <?php echo $target; ?> onClick="<?php echo $zipSearchInternetOutboundClick ?>"><?php echo $cta_text; ?></a>
						<?php if ($cta_link2): ?>
							<a href="<?php echo $cta_link2; ?>" <?php echo $target2; ?> onClick="<?php echo $zipSearchInternetOutboundClick2 ?>"><?php echo $cta_text2; ?></a>
						<?php endif; ?>
                        <?php if($has_plans) : ?>
                        <div class="view-plan-info">
                            <a href="/providers/<?php echo strtolower($provider['name']); ?>?plans=show">View plan information</a>
                        </div>
                        <?php endif; ?>
					</div>
					<div class="col-xl-12 provider_box provider-more-info">
						<div id="collapse<?php echo $counter ?>" class="collapse collapse-box <?php echo ($zip_qual)?'show':''; ?>" aria-labelledby="heading<?php echo $counter ?>" data-parent="#accordion">
							<div class="collapsed-container">
								<ul class="nav nav-tabs" id="providerTab" role="tablist">
									<li class="nav-item">
										<a class="nav-link active" id="plan-highlights-tab" data-toggle="tab" href="#plan-highlights<?php echo $counter ?>" role="tab" aria-controls="plan-highlights" aria-selected="true">Plan Highlights</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="terms-conditions-tab" data-toggle="tab" href="#terms-conditions<?php echo $counter ?>" role="tab" aria-controls="terms-conditions" aria-selected="false">Terms & Conditions</a>
									</li>
									</ul>
									<div class="tab-content" id="providerTabContent">
									<div class="tab-pane fade active show plan-highlights" id="plan-highlights<?php echo $counter ?>" role="tabpanel" aria-labelledby="plan-highlights-tab">

										<?php if (is_array($internet_plan_highlights)) : ?>
											<ul>
												<?php foreach($internet_plan_highlights as $highlight) : ?>
													<li>
														<svg xmlns="http://www.w3.org/2000/svg" class="svg-check" viewBox="0 0 20 20" fill="currentColor">
															<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
														</svg>
														<div><?php echo $highlight['feature']; ?></div>
													</li>
												<?php endforeach; ?>
											</ul>
										<?php endif; ?>
									</div>
									<div class="tab-pane fade terms-conditions" id="terms-conditions<?php echo $counter ?>" role="tabpanel" aria-labelledby="terms-conditions-tab"><?php echo $internet_terms_conditions; ?></div>
							
								</div>
							</div> 
						</div>
							
						<button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse<?php echo $counter ?>" aria-expanded="true" aria-controls="collapse<?php echo $counter ?>">
							<div>
								<div class="detail-text">View Details</div>
								<span class="material-icons">expand_less</span>
							</div>
						</button>
							
					</div>
				</div>
			</div>
				<?php 
				$counter ++;
				endforeach;
	                                        
	        //dataLayer info    
	           $zipSearchDataInternetWrapper = dataLayerProductImpressionWrapperWZipCode($zipcode, $zipSearchInternet);


	        echo "<script> function zipSearchDataInternetWrapper() {dataLayer.push({ ecommerce: null }); ".$zipSearchDataInternetWrapper ."  } </script>";
			endif;	
    	
		?>
	</div>

	<div class="tab-pane fade tv-search <?php echo $tv_active.' '.$tv_show; ?>" id="tv-search" role="tabpanel" aria-labelledby="tv-search-tab">
    	<?php 
    	if (empty($results_arr['tv'])): ?>
			<div class="container">
				<h2 class="no-results-header">No Results found for "<?php echo $no_results; ?>"</h2>
				<p class="demote"> Try searching again using different service type or zip code. </p>
			</div>
              <script> function zipSearchDataTVWrapper() { } </script>
			<?php 
		else:
			foreach($results_arr['tv'] as $provider): 
				$logo = get_field('logo', $provider['id']);
				$channels = $provider['channels'];
				$ch_progress_bar_color = '';
				$tv_info = get_field('tv', $provider['id']);
				$buyer_id = get_field('buyer', $provider['id']);
				$campaigns = get_field('campaign', $buyer_id);
				$campaign_key = array_search (  $provider['id'], array_column($campaigns, 'campaign_name') );
				$partnership_type = $campaigns[$campaign_key]['type_of_partnership'];
				$partner = get_field('partner', $provider['id']);
                $has_plans = get_field('show_plans_page', $provider['id']);
                $partner = get_field('partner', $provider['id']);
                $cta_link2 = '';
        
        
                if($partner){
					foreach($campaigns as $key => $camp) {
						$type_of_partnership = $camp['type_of_partnership'];

						if($camp['campaign_name'] == $provider['id']){
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
			                        $cta_text2 = '<p class="mt-2 mb-0 tel-link font-weight-bold text-center">Order Online</p>';
			                        $cta_link = 'tel:'.$camp['call_center'];
			                        $cta_link2 = $camp['digital_tracking_link'];
			                        $target2='target="_blank"';
			                    } else {
			                        $cta_text = 'Order Online';
			                        $cta_text2 = '<p class="mt-2 mb-0 text-center small-text">Call to order:<span class="tel-link font-weight-bold"> '.$camp['call_center'].'</span></p>';
			                        $cta_link = $camp['digital_tracking_link'];
			                        $cta_link2 = 'tel:'.$camp['call_center'];
			                        $target='target="_blank"';
			                    }
			                }
			            }
			        }
						
				}else{
					$cta_text = 'View Plans';
					$cta_link = get_field('brands_website_url',$provider['id']);
				}
                
                $counterTV++;
  
                $zipSearchTVIndv = zipSearchLoadIndv($provider, $zipSearchTVVariant, $counterTV, "TV");
    
                $zipSearchTV .= $zipSearchTVIndv; 
        
                $zipSearchTVProdClick = zipSearchProdClick($zipcode, $provider, $zipSearchTVVariant, $counterTV, "TV");
                
                $zipSearchTVOutboundClick = dataLayerOutboundLinkClick( $provider['id'], "TV", $cta_link );

                if ($cta_link2){
        			$zipSearchTVOutboundClick2 = dataLayerOutboundLinkClick( $provider['id'], "TV", $cta_link2 );
        		}
                
                
                
				?>
			<div class="container zip-container" data-channel="<?php echo $channels ?>" data-cost="<?php echo $provider['cost'] ?>">
				<div class="row provider-box-row">
					<div class="col-xl col-12 provider_box img-box div-rt-lg div-rt-md div-btm-img">
						<div class="img-wrap">
							<a href="<?php echo get_permalink($provider['id']); ?>" onClick="<?php echo $zipSearchTVProdClick ?>">
								<img src="<?php echo $logo;  ?>" alt="<?php echo get_the_title($provider['id']) ?>" height="50" width="127">
							</a>
						</div>
					</div>
					<div class="col-lg col-12 provider_box div-rt-lg div-rt-md div-btm-lf">
						<p>Channels</p>
						<?php if($channels != 'N/A'): ?>
						<p class="large-text"><?php echo $channels; ?>+</p>
						<?php else: ?>
						<p class="large-text"><?php echo $channels; ?></p>
						<?php endif; ?>
					</div>
					<div class="col-lg col-12 provider_box div-rt-lg div-btm-lf">
						<p>Starting at</p>
						<?php if($provider['cost'] != 'N/A'): ?>
						<p class="large-text">$<?php echo $provider['cost']; ?>/mo</p>
						<?php else: ?>
						<p class="large-text"><?php echo $provider['cost']; ?></p>
						<?php endif; ?>
					</div>
					<div class="col-lg-3 provider_box plan_link">
						<a href="<?php echo $cta_link; ?>" class="cta_btn" <?php echo $target; ?> onClick="<?php echo $zipSearchTVOutboundClick ?>"><?php echo $cta_text; ?></a>
						<?php if ($cta_link2): ?>
							<a href="<?php echo $cta_link2; ?>" <?php echo $target2; ?> onClick="<?php echo $zipSearchTVOutboundClick2 ?>"><?php echo $cta_text2; ?></a>
						<?php endif; ?>
                        <?php if($has_plans) : ?>
                        <div class="view-plan-info">
                            <a href="/providers/<?php echo strtolower($provider['name']); ?>?plans=show">View plan information</a>
                        </div>
                        <?php endif; ?>
					</div>

					<div class="col-xl-12 provider_box provider-more-info">
							<div id="collapse<?php echo $counter ?>" class="collapse collapse-box <?php echo ($zip_qual)?'show':''; ?>" aria-labelledby="heading<?php echo $counter ?>" data-parent="#accordion">
								<div class="collapsed-container">
									<ul class="nav nav-tabs" id="providerTab" role="tablist">
										<li class="nav-item">
						                  <a class="nav-link active" id="plan-highlights-tab" data-toggle="tab" href="#plan-highlights<?php echo $counter ?>" role="tab" aria-controls="plan-highlights" aria-selected="true">Plan Highlights</a>
						                </li>
						                <li class="nav-item">
						                  <a class="nav-link" id="terms-conditions-tab" data-toggle="tab" href="#terms-conditions<?php echo $counter ?>" role="tab" aria-controls="terms-conditions" aria-selected="false">Terms & Conditions</a>
						                </li>
						              </ul>
						              <div class="tab-content" id="providerTabContent">
						              	<div class="tab-pane fade active show plan-highlights" id="plan-highlights<?php echo $counter ?>" role="tabpanel" aria-labelledby="plan-highlights-tab">
						              		<?php if (is_array($tv_info['plan_highlights'])) : ?>
												<ul>
													<?php foreach($tv_info['plan_highlights'] as $highlight) : ?>
														<li>
														  	<svg xmlns="http://www.w3.org/2000/svg" class="svg-check" viewBox="0 0 20 20" fill="currentColor">
																<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
															</svg>
															<div><?php echo $highlight['feature']; ?></div>
														</li>
													<?php endforeach; ?>
												</ul>
						              		<?php endif; ?>
						              	</div>
						                <div class="tab-pane fade terms-conditions" id="terms-conditions<?php echo $counter ?>" role="tabpanel" aria-labelledby="terms-conditions-tab"><?php echo $tv_info['terms_&_conditions']; ?></div>
					           
					              </div>
					            </div> 
							</div>

							<button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse<?php echo $counter ?>" aria-expanded="true" aria-controls="collapse<?php echo $counter ?>">
								<div>
									<div class="detail-text">View Details</div>
									<span class="material-icons">expand_less</span>
								</div>
							</button>
							
					</div>
				</div>
			</div>
			<?php 
			$counter ++;
			endforeach; 
                                        
            //dataLayer info    
            $zipSearchDataTVWrapper = dataLayerProductImpressionWrapperWZipCode($zipcode, $zipSearchTV);


        echo "<script> function zipSearchDataTVWrapper() {dataLayer.push({ ecommerce: null }); ".$zipSearchDataTVWrapper ."  } </script>";
                                        
		endif;	?>
    </div>

    <div class="tab-pane fade bundle-search <?php echo $bundle_active.' '.$bundle_show; ?>" id="bundle-search" role="tabpanel" aria-labelledby="bundle-search-tab">
    	<?php 
    	if (empty($results_arr['bundles'])): ?>
			<div class="container">
				<h2 class="no-results-header">No Results found for "<?php echo $no_results; ?>"</h2>
				<p class="demote"> Try searching again using different service type or zip code. </p>
			</div>
          <script> function zipSearchDataBundleWrapper() { } </script>
			<?php 
		else:
			foreach($results_arr['bundles'] as $provider): 
				$logo = get_field('logo', $provider['id']);
				$download_speed = $provider['download_speed'];
				$ds_progress_bar_color = '';
				$channels = $provider['channels'];
				$ch_progress_bar_color = '';
				$bundle_info = get_field('bundles', $provider['id']);
				$buyer_id = get_field('buyer', $provider['id']);
				$campaigns = get_field('campaign', $buyer_id);
				$campaign_key = array_search (  $provider['id'], array_column($campaigns, 'campaign_name') );
				$partnership_type = $campaigns[$campaign_key]['type_of_partnership'];
				$partner = get_field('partner', $provider['id']);
                $has_plans = get_field('show_plans_page', $provider['id']);
                $partner = get_field('partner', $provider['id']);
                $cta_link2 = '';
        
        
                if($partner){
					foreach($campaigns as $key => $camp) {
						$type_of_partnership = $camp['type_of_partnership'];

						if($camp['campaign_name'] == $provider['id']){
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
			                        $cta_text2 = '<p class="mt-2 mb-0 tel-link font-weight-bold text-center">Order Online</p>';
			                        $cta_link = 'tel:'.$camp['call_center'];
			                        $cta_link2 = $camp['digital_tracking_link'];
			                        $target2='target="_blank"';
			                    } else {
			                        $cta_text = 'Order Online';
			                        $cta_text2 = '<p class="mt-2 mb-0 text-center small-text">Call to order:<span class="tel-link font-weight-bold"> '.$camp['call_center'].'</span></p>';
			                        $cta_link = $camp['digital_tracking_link'];
			                        $cta_link2 = 'tel:'.$camp['call_center'];
			                        $target='target="_blank"';
			                    }
			                }
			            }
			        }
						
				}else{
					$cta_text = 'View Plans';
					$cta_link = get_field('brands_website_url',$provider['id']);
				}
                
                $counterBundle++;
  
                $zipSearchBundleIndv = zipSearchLoadIndv($provider, $zipSearchBundleVariant, $counterBundle, "Bundle");
                    
                $zipSearchBundle .= $zipSearchBundleIndv;  
        
                $zipSearchBundleProdClick = zipSearchProdClick($zipcode, $provider, $zipSearchBundleVariant, $counterBundle, "Bundle");
                
                $zipSearchBundleOutboundClick = dataLayerOutboundLinkClick( $provider['id'], "Bundle", $cta_link );
            
        		if ($cta_link2){
        			$zipSearchBundleOutboundClick2 = dataLayerOutboundLinkClick( $provider['id'], "Bundle", $cta_link2 );
        		}
        
				?>
			<div class="container zip-container" data-channel="<?php echo $channels ?>" data-download="<?php echo $download_speed ?>" data-cost="<?php echo $provider['cost'] ?>">
				<div class="row provider-box-row">
					<div class="col-xl col-12 provider_box div-rt-lg img-box div-rt-md div-btm-img">
						<div class="img-wrap">
							<a href="<?php echo get_permalink($provider['id']); ?>" onClick="<?php echo $zipSearchBundleProdClick ?>">
								<img src="<?php echo $logo;  ?>" alt="<?php echo get_the_title($provider['id']) ?>" height="50" width="127">
							</a>
						</div>
					</div>
					<div class="col-lg col-12 provider_box div-rt-lg div-btm-lf">
						<p>Starting at</p>
						<?php if($provider['cost'] != 'N/A'): ?>
						<p class="large-text">$<?php echo $provider['cost']; ?>/mo</p>
						<?php else: ?>
						<p class="large-text"><?php echo $provider['cost']; ?></p>
						<?php endif; ?>
					</div>
					<div class="col-lg col-12 provider_box div-rt-lg div-rt-md div-btm-lf">
						<p>Max Download</p>
						<?php if($download_speed != 'N/A'): ?>
						<p class="large-text"><?php echo $download_speed; ?> Mbps</p>
						<?php else: ?>
						<p class="large-text"><?php echo $download_speed; ?></p>
						<?php endif; ?>
					</div>
					<div class="col-lg col-12 provider_box div-rt-lg div-rt-md div-btm-lf">
						<p>Channels</p>
						<?php if($channels != 'N/A'): ?>
						<p class="large-text"><?php echo $channels; ?>+</p>
						<?php else: ?>
						<p class="large-text"><?php echo $channels; ?></p>
						<?php endif; ?>
					</div>
					<div class="col-lg-3 col-12 provider_box plan_link">
						<a href="<?php echo $cta_link; ?>" class="cta_btn" <?php echo $target; ?> onClick="<?php echo $zipSearchBundleOutboundClick ?>"><?php echo $cta_text; ?></a>
						<?php if ($cta_link2): ?>
							<a href="<?php echo $cta_link2; ?>" <?php echo $target2; ?> onClick="<?php echo $zipSearchBundleOutboundClick2 ?>"><?php echo $cta_text2; ?></a>
						<?php endif; ?>
                        <?php if($has_plans) : ?>
                        <div class="view-plan-info">
                            <a href="/providers/<?php echo strtolower($provider['name']); ?>?plans=show">View plan information</a>
                        </div>
                        <?php endif; ?>
					</div>
					<div class="col-xl-12 provider_box provider-more-info">
							<div id="collapse<?php echo $counter ?>" class="collapse collapse-box <?php echo ($zip_qual)?'show':''; ?>" aria-labelledby="heading<?php echo $counter ?>" data-parent="#accordion">
								<div class="collapsed-container">
									<ul class="nav nav-tabs" id="providerTab" role="tablist">
										<li class="nav-item">
						                  <a class="nav-link active" id="plan-highlights-tab" data-toggle="tab" href="#plan-highlights<?php echo $counter ?>" role="tab" aria-controls="plan-highlights" aria-selected="true">Plan Highlights</a>
						                </li>
						                <li class="nav-item">
						                  <a class="nav-link" id="terms-conditions-tab" data-toggle="tab" href="#terms-conditions<?php echo $counter ?>" role="tab" aria-controls="terms-conditions" aria-selected="false">Terms & Conditions</a>
						                </li>
						              </ul>
						              <div class="tab-content" id="providerTabContent">
						              	<div class="tab-pane fade active show plan-highlights" id="plan-highlights<?php echo $counter ?>" role="tabpanel" aria-labelledby="plan-highlights-tab">

											<?php if (is_array($bundle_info['plan_highlights'])) : ?>
												<ul>
						              				<?php foreach($bundle_info['plan_highlights'] as $highlight) : ?>
														<li>
														  	<svg xmlns="http://www.w3.org/2000/svg" class="svg-check" viewBox="0 0 20 20" fill="currentColor">
																<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
															</svg>
															<div><?php echo $highlight['feature']; ?></div>
														</li>
						              				<?php endforeach; ?>
						              			</ul>
											<?php endif; ?>
						              	</div>
						                <div class="tab-pane fade terms-conditions" id="terms-conditions<?php echo $counter ?>" role="tabpanel" aria-labelledby="terms-conditions-tab"><?php echo $bundle_info['terms_&_conditions']; ?></div>
					           
					              </div>
					            </div> 
							</div>
							
							<button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse<?php echo $counter ?>" aria-expanded="true" aria-controls="collapse<?php echo $counter ?>">
								<div>
									<div class="detail-text">View Details</div>
									<span class="material-icons">expand_less</span>
								</div>
							</button>
							
					</div>
				</div>
			</div>
			<?php 
			$counter ++;
			endforeach; 
            
            //dataLayer info  
             $zipSearchDataBundleWrapper = dataLayerProductImpressionWrapperWZipCode($zipcode, $zipSearchBundle);



        echo "<script> function zipSearchDataBundleWrapper() { dataLayer.push({ ecommerce: null });  ".$zipSearchDataBundleWrapper ."   } </script>";
                                        
		endif;	?>
    </div>
</div>

<?php 
if ($type == 'internet'){
	echo "<script> zipSearchDataInternetWrapper(); </script>";
} elseif($type == 'tv'){
	echo "<script> zipSearchDataTVWrapper(); </script>";
} elseif ($type == 'bundle'){
    echo "<script> zipSearchDataBundleWrapper(); </script>";
}

?>