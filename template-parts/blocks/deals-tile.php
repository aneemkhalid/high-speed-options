<?php

/**
 * Deals Tile Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 * 
 */ 
$providers = get_field('select_providers');
$main_title = get_field('main_title');

$dealsTilesCounter = 0;

$providerId = $provider['select_provider'];

if(!empty($main_title))
     echo '<h2>'.$main_title.'</h2>';
if($providers):
     echo '<section class="deals_tile_main"> ';
     $ind = 0;
     foreach ($providers as $key => $provider) {
          $pID = $provider['select_provider'];
          $description = $provider['description'];
          $show_callout = $provider['show_callout'];
          //$contracts = get_field('contracts', $pID);
          $logo = get_field('logo',$pID);
          $internet_check = get_field('internet_check',$pID);
          if($internet_check){
               $internet = get_field('internet',$pID);
               //echo "<pre>"; print_r($internet); echo "</pre>";
               // Connection is Splitout
               if($internet["split_out_connection"] == 1){
                    $ConectionTypes = $internet['connection_types'];
                    foreach ( $ConectionTypes as $ConectionType ){
                         $ConType = $internet[$ConectionType . '_connection'];
                         // Starting Price
                         $prices[$ind][] = $ConType[$ConectionType . '_' . "starting_price"];
                         // Max Download Speed
                         $speeds[$ind][] = $ConType[$ConectionType . '_' . "max_download_speed"];
                         // Data Caps
                         $dataCaps[$ind][] = $ConType[$ConectionType . '_' . "data_caps"];

                    }
                    // Data Caps Value
                    foreach($dataCaps as $dataCap) {
                         $dataCapVal = ''; $YesAns = 0; $NoAns = 0;
                         foreach ( $dataCap as $Ans ){
                         if ( strtolower($Ans) == "yes" ){
                              $YesAns++;
                         }else{
                              $NoAns++;
                         }
                         if ( $YesAns >= 1 ){
                                   $data_caps = 'Yes';
                         }else{
                                   $data_caps = 'No';
                         }
                         }
                    }
                    // Min & Max Starting Price
                    foreach($prices as $price){

                         $minStartingPrice = min(array_filter($price, function($value) {
                              return ($value !== null && $value !== false && $value !== ''); 
                         }));
                         $maxStartingPrice = max(array_filter($price, function($value) {
                              return ($value !== null && $value !== false && $value !== ''); 
                         }));
                         if ($minStartingPrice == $maxStartingPrice ){
                              $starting_price = "$" . $maxStartingPrice;  
                         } elseif ($minStartingPrice == ""){
                              $starting_price = "$" . $maxStartingPrice;
                         } elseif ($maxStartingPrice == ""){
                              $starting_price = "$" . $minStartingPrice;
                         } else {
                              $starting_price = "$" . $minStartingPrice . "-$" . $maxStartingPrice;
                         }
                    }
                    // Min & Max Download Speed
                    foreach($speeds as $speed){
                         $minDownloadSpeed = min(array_filter($speed, function($value) {
                              return ($value !== null && $value !== false && $value !== ''); 
                          }));
                         $maxDownloadSpeed = max(array_filter($speed, function($value) {
                              return ($value !== null && $value !== false && $value !== ''); 
                          }));
                         
                         if ($minDownloadSpeed == $maxDownloadSpeed ){
                              $speed = $maxDownloadSpeed;
                         } elseif ($minDownloadSpeed == ""){
                              $speed = $maxDownloadSpeed;
                         } elseif ($maxDownloadSpeed == ""){
                              $speed = $minDownloadSpeed;
                         } else {
                              $speed = $minDownloadSpeed . "-" . $maxDownloadSpeed   ;
                         }
                    }
               } else {
               $minVal = $internet['details']['min_starting_price'];
               $maxVal = $internet['details']['max_starting_price'];
               if ($minVal == $maxVal ){
                    $starting_price = "$" . $maxVal;  
                } elseif ($minVal == ""){
                    $starting_price = "$" . $maxVal;
                } elseif ($maxVal == ""){
                    $starting_price = "$" . $minVal;
                } else {
                    $starting_price = "$" . $minVal . "-$" . $maxVal;
                }
               $minDownloadSpeed = $internet['details']['min_download_speed'];
               $maxDownloadSpeed = $internet['details']['max_download_speed'];
               if ($minDownloadSpeed == $maxDownloadSpeed ){
                    $speed = $maxDownloadSpeed;  
                } elseif ($minDownloadSpeed == ""){
                    $speed = $maxDownloadSpeed;
                } elseif ($maxDownloadSpeed == ""){
                    $speed = $minDownloadSpeed;
                } else {
                    $speed = $minDownloadSpeed . "-" . $maxDownloadSpeed;
                }
               $data_caps = ucfirst($internet['details']['data_caps']);
               }
          }else{
               $internet = '';
               $starting_price = '';
               $contracts = '';
               $data_caps = '';
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
                      'text' => 'Deals Tile'
            ]; 
         
         
           $dealsTilesSlug = get_post_field( 'post_name', get_post() );
          $dealsTilesCounter++;
          $dealsTilesProductClick = dataLayerProdClick($pID, $variantProvider, $dealsTilesCounter,  $dealsTilesSlug, $main_title);
          ?>
          
          <div class="deals_tile">
               <div class="head">
                    <?php 
                    if(!empty($logo)) echo '<a href="'.get_the_permalink($pID).'" onclick="'.$dealsTilesProductClick.'"><img src="'.$logo.'"></a>';          
                    if($show_callout)
                         echo '<div class="callout-text"><p><span class="material-icons">check_circle</span>'.get_field('superlative',$pID).'</p></div>';       
                    ?>
               </div>
               <div class="tile_detail">
                    <?php 
                         if(!empty($starting_price))
                              echo '<div class="price wrap"> <img src="'.get_template_directory_uri().'/images/price_icon.svg"><h6>Price:<span>'.$starting_price.'/mo</span></h6></div> ';
                         if(!empty($speed))
                              echo '<div class="speed wrap"> <img src="'.get_template_directory_uri().'/images/speed_icon.svg"><h6>Speeds:<span>up to '.$speed. ' Mbps</span></h6></div> ';
                         // if(!empty($contracts))
                         //      echo '<div class="contract wrap"> <img src="'.get_template_directory_uri().'/images/contracts_icon.svg"><h6>Contracts:<span>'.$contracts.'</span></h6></div> ';
                         if(!empty($data_caps))
                              echo '<div class="data_cap wrap"> <img src="'.get_template_directory_uri().'/images/data_caps_icon.svg"><h6>Data Caps:<span>'.$data_caps.'</span></h6></div> ';
                    ?>
               </div>
               <?php if(!empty($description)) echo $description; ?>

               <?php  

                    $data_att = '';
                    $internet_checked = '';
                    $tv_checked = '';
                    $bundle_checked = '';
                    $zip_popup_class = '';
                    $partner = get_field('partner', $pID);
                    if($partner){
                         $buyer_id = get_field('buyer', $pID);
                         $campaign = get_field( "campaign", $buyer_id );
                         foreach($campaign as $key => $camp) {
                              $type_of_partnership = $camp['type_of_partnership'];
                              if($camp['campaign_name'] == $pID){
                                   if($type_of_partnership == 'call_center'){
                                        $cta_text = '<span class="material-icons">call</span>'.$camp['call_center'];
                                        $cta_link = 'tel:'.$camp['call_center'];
                                   }else{
                                        $cta_text = 'View Plans';
                                        $cta_link = $camp['digital_tracking_link'];
                                   }
                              }
                              
                         }			
                    }else{
                         $cta_text = 'View Plans';
                         $cta_link = get_field('brands_website_url',$pID);
                    }

                    $button_type = $provider['provider_card_button_type'];
                    if ($button_type == 'popup'){
                         $cta_text = $provider['provider_card_button_text'];
                         $cta_link = '#';
                         $rand = rand();
                         $data_att = 'data-toggle="modal" data-target="#zipPopupModal-'.$rand.'"';
                         $zip_popup_class = 'zip-popup-btn';
                         $default_tab = $provider['provider_card_default_tab'];
                         if ($default_tab == 'internet'){
                              $internet_checked = 'checked';
                         } elseif ($default_tab == 'tv'){
                              $tv_checked = 'checked';
                         } elseif ($default_tab == 'bundle'){
                              $bundle_checked = 'checked';
                         }
                        
                        //dataLayer info
                        $dataInfoSlug = get_post_field( 'post_name', get_post() ) ;
                        $dataCheckAvailOnClick = 'onclick="'.dataLayerCheckAvailabilityClick($pID, $dataInfoSlug).'"';
            
                        
                         require get_theme_file_path( '/template-parts/zip-search-popup.php' );
                    }
               if(!empty($cta_text)) echo '<div class="check_availability"><a href="'.$cta_link.'" class="cta_btn '.$zip_popup_class.'" target="_blank" '.$data_att.' '.$dataCheckAvailOnClick.'>'.$cta_text.'</a></div>';
               ?>
          </div>

          <?php
           $ind++;
     }
     echo '</section>';
endif;