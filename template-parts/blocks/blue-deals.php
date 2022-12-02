<?php

/**
 * FAQ List Block.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

$title = get_field('title');
$bbody = get_field('blue_body');
$wbody = get_field('white_body');
$heading = get_field('heading');
$include = get_field('include');
$today = date('Ymd');
$expire = get_field('expiration_date');
$color = get_field('color_override');
$cta_type = get_field('cta_type');

$show_cta = false;

if( $expire > $today || $expire == ""  ){

    if($id = get_field('provider')) {

        $cta_text = $cta_link = $cta_text2 = $cta_link2 = $data_att = $logo = $cta_class = $target = $target2 = $datalayer = '';

        if( $include && in_array('logo', $include) ) {
            $logo = get_field('logo', $id);
            $alt = get_the_title($id);
        }
        if( $include && in_array('cta', $include) ) {
            $show_cta = true;
        }

        $link = get_permalink($id);

        $partner = get_field('partner', $id);

        $providerName = get_the_title($id);

        //Datalayer for logo click
        global $post;
        $picksPageSlug = $post->post_name;
        $picksPageSlug = ucwords(str_replace("-", " ", $picksPageSlug));

        $picksLayerList = 'Blue Deals - ' . $picksPageSlug;
        
        $variantProvider = [
            'text' => $picksLayerList
        ];

        $picksLogoClick = dataLayerProdClick($id, $variantProvider, 1, $picksPageSlug, $picksLayerList);

        if($cta_type == 'popup') {
            $cta_text = 'Check Availability';
            $cta_link = '#';
            $rand = rand();
            $data_att = 'data-toggle="modal" data-target="#zipPopupModal-'.$rand.'"';
            $cta_class = 'zip-popup-btn';
            $internet_checked = 'checked';
           
            require get_theme_file_path( '/template-parts/zip-search-popup.php' );

            $dataInfoSlug = get_post_field( 'post_name', get_post() ) ;
            $datalayer = dataLayerCheckAvailabilityClick($id, $dataInfoSlug);
        } elseif($cta_type == 'provider') {
            $cta_text = 'Learn more about '.$providerName;
            $cta_link = $link;
        } else {
            if($partner){
                $buyer_id = get_field('buyer', $id);
                $campaign = get_field( "campaign", $buyer_id );

                foreach($campaign as $key => $camp) {
                    $type_of_partnership = $camp['type_of_partnership'];
                    if($camp['campaign_name'] == $id){

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
                $cta_link = get_field('brands_website_url', $id);
                $target = 'target="_blank"';
            }

            $datalayer = dataLayerOutboundLinkClick( $id, $picksLayerList, $cta_link );
            $datalayer2 = dataLayerOutboundLinkClick( $id, $picksLayerList, $cta_link2 );
        }
    }

    ?>

    <section class="blue-deals-block <?php echo ($title) ? 'has-title' : ''; ?>">
        <div class="deal-container">
            <div class="blue-content" <?php echo ($color) ? 'style="background-color:' . $color . ';"' : ''; ?>>
                <div class="logo-container">
                    <?php if($logo) : ?>
                        <a href="<?php echo $link ?>" onclick="<?php echo $picksLogoClick ?>">
                            <img src="<?php echo $logo ?>" alt="<?php echo $alt; ?>" height="70" width="auto">
                        </a> 
                    <?php endif; ?>
                </div>
                <h3><?php echo $heading ?></h3>
                <div>
                    <?php echo $bbody ?>
                </div>
            </div>
            <?php if($wbody) : ?>
            <div class="white-content">
                <div>
                    <?php echo $wbody ?>
                </div>
            </div>
            <?php endif; ?>
            <?php if($show_cta) : ?>
                <div class="cta-container">
                    <?php if(!empty($cta_text)) echo '<a href="'.$cta_link.'" class="cta_btn ' . $cta_class .'" '.$target.' onclick="' . $datalayer . '"' . $data_att . '>'.$cta_text.'</a>'; ?>
                    <?php if(!empty($cta_text2)) echo '<a href="'.$cta_link2.'" class="plans-btn ' . $cta_class .'" '.$target2.' onclick="' . $datalayer2 . '"' . $data_att . '>'.$cta_text2.'</a>'; ?>
                </div>
            <?php endif; ?>
            <?php if($title) : ?>
                <div class="title-container"><?php echo $title ?></div>
            <?php endif; ?>
        </div>
    </section>
<?php } ?>