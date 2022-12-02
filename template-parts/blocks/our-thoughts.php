<?php

/**
 * Our Thoughts Block
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

$body = get_field('body');
$heading = get_field('heading');
$disclaimer = get_field('disclaimer');

$animation_path = '
         <picture class="lottie">
           <source type="image/apng" srcset="'. get_template_directory_uri() . '/images/animations/glasses.apng">
           <img src="'. get_template_directory_uri() . 'images/animations/glasses-fallback.svg"  width="80" height="80"  alt="Glasses Looking">
         </picture>
    ';

$id = get_field('provider');
if($id) {


    $logo = get_field('logo', $id);

    $link = get_permalink($id);

    $partner = get_field('partner', $id);

    $providerName = get_the_title($id);

    //Datalayer for logo click
    global $post;
    $picksPageSlug = $post->post_name;
    $picksPageSlug = ucwords(str_replace("-", " ", $picksPageSlug));

    $picksLayerList = 'Our Thoughts - ' . $picksPageSlug;

    $variantProvider = [
        'text' => $picksLayerList
    ];

    $picksLogoClick = dataLayerProdClick($id, $variantProvider, 1, $picksPageSlug, $picksLayerList);

    $cta_text = '';
    $cta_link = '';
    $cta_text2 = '';
    $cta_link2 = '';

    if($partner){
        $buyer_id = get_field('buyer', $id);
        $campaign = get_field( "campaign", $buyer_id );
        $target = $target2 = $cta_link2 = $cta_text2='';

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

    if ($cta_link2){
        $datalayer2 = dataLayerOutboundLinkClick( $id, $picksLayerList, $cta_link2 );
    }
}

?>

<section class="our-thoughts-block">
    <div class="thoughts-container">
        <div class="blue-container col d-flex p-4 p-md-5">
            <div class="lottie-container">
                <?php echo $animation_path ?>
            </div>
            <div>
                <h4><?php echo $heading ?></h4>
                <div class="body-container">
                    <?php echo $body ?>
                </div>
            </div>
        </div>
        <?php if ($id): ?>
        <div class="provider-info col col-sm-6 col-md-4 p-3">
            <div class="logo-container">
                <?php if($logo) : ?>
                    <a href="<?php echo $link ?>" onclick="<?php echo $picksLogoClick ?>">
                        <img src="<?php echo $logo ?>" alt="<?php echo $providerName ?>" width="200" height="80">
                    </a> 
                <?php endif; ?>
            </div>
            <div class="cta-container flex-column align-items-center">
                <?php
                    if(!empty($cta_text)) echo '<a href="'.$cta_link.'" class="cta_btn" '.$target.' onclick="' . $datalayer . '">'.$cta_text.'</a>';
                    if(!empty($cta_text2)) echo '<a href="'.$cta_link2.'" '.$target2.' onclick="' . $datalayer2 . '">'.$cta_text2.'</a>';
                ?>
            </div>
        </div>
    <?php endif; ?>
    </div>
    <?php if($disclaimer) : ?>
        <div class="disclaim-container">
            <?php echo $disclaimer ?>
        </div>
    <?php endif; ?>
</section>