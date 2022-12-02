<?php

/**
 * Top Providers Block.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */ 

$provider_type = get_field('provider_type');
$heading = get_field('heading');
if($provider_type == 'internet_tv'){
    $providers = get_field('internettv_providers');
}else{
    $providers = get_field('streaming_providers');
}
$source = get_field('source');

$providersBoxPlansClick = $dataCheckAvailOnClick = '';
$providerCounter = 0;

if($providers){
    // print_r($providers);
    ?>
    <section class="best_service_providers top-wifi-provider">
        <?php if(!empty($heading)) echo '<h2>'.$heading .'</h2>'; ?>
        <div class="row">
            <?php 
                foreach ($providers as $provider) { 
                    $pID = $provider['provider'];
                    $logo = get_field('logo',$pID);
                    $title = get_the_title($pID);
                    $add_features = $provider['add_features'];
                    if($add_features == 'default'){
                        if($provider_type == 'internet_tv'){
                            $features = get_field('main_features',$pID);
                        }else{
                            $features = get_field('feature_highlights',$pID);
                        }
                    }else{
                        $features = $provider['features'];
                    }
                    

                    $data_att = '';
                    $internet_checked = '';
                    $tv_checked = '';
                    $bundle_checked = '';
                    $zip_popup_class = '';
                    $target = $target2 = $cta_link2 = $cta_text2 = '';
                    
                    if($provider_type == 'streaming'){
                        $cta_text = 'View Plans';
                        $cta_link = get_field('link',$pID);
                        
                        $variantProvider = [
                                'text' => 'View Plans',
                                'url' => $cta_link
                        ];

                         $datalayer = dataLayerOutboundLinkClick( $pID, $provider_type, $cta_link );
                        
                    }else{
                        $partner = get_field('partner', $pID);
                        $button_type = $provider['provider_card_button_type'];
                        if ($button_type == 'popup'){
                            $cta_text = $provider['provider_card_button_text'];
                            $cta_link = '#';
                            $rand = rand();
                            $data_att = 'data-toggle="modal" data-target="#zipPopupModal-'.$rand.'"';
                            $zip_popup_class = 'zip-popup-btn';
                            $default_tab = $provider['provider_card_default_tab'];
                            
                            
                             //dataLayer info
                            $dataInfoSlug = get_post_field( 'post_name', get_post() ) ;
                            $dataCheckAvailOnClick =dataLayerCheckAvailabilityClick($pID, $dataInfoSlug);
                            
                            if ($default_tab == 'internet'){
                                $internet_checked = 'checked';
                            } elseif ($default_tab == 'tv'){
                                $tv_checked = 'checked';
                            } elseif ($default_tab == 'bundle'){
                                $bundle_checked = 'checked';
                            }
                            require get_theme_file_path( '/template-parts/zip-search-popup.php' );
                        } else {
                            if($partner){
                                $buyer_id = get_field('buyer', $pID);
                                $campaign = get_field( "campaign", $buyer_id );
                                foreach($campaign as $key => $camp) {
                                    $type_of_partnership = $camp['type_of_partnership'];
                                    if($camp['campaign_name'] == $pID){
                                        
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
                                $cta_link = get_field('brands_website_url',$pID);
                            }
                        }

                        $datalayer = dataLayerOutboundLinkClick( $pID, $provider_type, $cta_link );

                        if ($cta_link2){
                            $datalayer2 = dataLayerOutboundLinkClick( $pID, $provider_type, $cta_link2 );
                        }

                    }
                    
                    //dataLayer info
                  /*  if ($cta_text === 'View Plans' ) {
                      $variantProvider = [
                                'text' => 'View Plans',
                                'url' => $cta_link
                        ];
                    } else {
                        $variantProvider = [
                                'text' => 'Call'
                        ]; 
                        
                    }*/
                      $variantProvider = [
                                'text' => 'Top Proivders Card'
                        ]; 
                        
                    $providerCounter++; 
                    
                    $providerSlug = get_post_field( 'post_name', get_post() );
                    
                    $providersBoxProductClick = dataLayerProdClick($pID, $variantProvider, $providerCounter,  $providerSlug, $heading);
                    
                    $logo_html = '';
                    if (!empty($logo)){
                        if ($provider_type == 'streaming'){
                            $logo_html = '<div class="img-wrap"><img src="'.$logo.'" alt="'.$title.'"></div>';
                        } else {
                            $logo_html = '<a href="'.get_the_permalink($pID).'" class="img-wrap"><img src="'.$logo.'" alt="'.$title.'" onclick="'.$providersBoxProductClick.'"> </a>';
                        }
                    }
                    
                    

                    ?>
                    <div class="col-sm-6">
                        <div class="best-service-provider-box">
                            <?php echo $logo_html; ?>
                            <div class="info">
                                <h4><?php echo $title; ?></h4>
                                <?php 
                                    if($features){
                                        echo '<ul>';
                                            foreach ($features as $feature) {
                                                if(!empty($feature["feature"]))
                                                    echo '<li>'.$feature["feature"].'</li>';
                                            }
                                        echo '</ul>';
                                    }
                                ?>
                                
                                <div class="check_availability">
                                    <?php if(!empty($cta_text)) echo '<a href="'.$cta_link.'" class="cta_btn font-weight-bold '.$zip_popup_class.'" '.$target.' '.$data_att.' onclick="'. $datalayer . $dataCheckAvailOnClick .'">'.$cta_text.'</a>'; ?>

                                    <?php if(!empty($cta_text2)) echo '<a href="'.$cta_link2.'" '.$target2.' class="font-weight-bold" onclick="'. $datalayer2 .'">'.$cta_text2.'</a>'; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php 
                }
            ?>
        </div>
    </section>
    <?php
    if ($source){
        echo '<figcaption class="figcaption-source best_service_providers-source">'.$source.'</figcaption>';
    }
    ?>
    <?php 
}